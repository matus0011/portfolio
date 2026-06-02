<script lang="ts">
  import { onMount } from "svelte";
  import * as THREE from "three";
  import { LineSegments2 } from "three/examples/jsm/lines/LineSegments2.js";
  import { LineSegmentsGeometry } from "three/examples/jsm/lines/LineSegmentsGeometry.js";
  import { LineMaterial } from "three/examples/jsm/lines/LineMaterial.js";
  import { gsap } from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";

  // --- Tunable look ---------------------------------------------------------
  const PAGE_BG = "#DDDBD6"; // musi odpowiada\u0107 --color-bg
  const LINE_COLOR = "#FE5030"; // --color-accent; zmie\u0144 na "#010101" dla ciemnych linii
  const LINE_OPACITY = 0.45;
  const LINE_WIDTH = 1.8; // grubo\u015b\u0107 linii w pikselach (grube linie Three.js)

  // Hover „spotlight" \u2014 linie w pobli\u017cu kursora rozja\u015bniaj\u0105 si\u0119 / nabieraj\u0105 akcentu
  const SPOT_COLOR = "#FF4A24"; // kolor blasku
  const SPOT_RADIUS = 24; // promie\u0144 poświaty (jednostki sceny)
  const SPOT_STRENGTH = 0.85; // si\u0142a (0..1) \u2014 \u015bredni efekt

  // Geometria siatki (jednostki sceny)
  const HALF_WIDTH = 170; // zasi\u0119g w lewo/prawo \u2014 z zapasem, by siatka zawsze
  //                          si\u0119ga\u0142a poza kraw\u0119dzie ekranu (pe\u0142na szeroko\u015b\u0107)
  const DEPTH = 260; // jak g\u0142\u0119boko siatka wchodzi w horyzont
  const NEAR_Z = 28; // jak daleko siatka si\u0119ga DO PRZODU (spod kamery), \u017ceby
  //                    wype\u0142ni\u0107 sam d\u00f3\u0142 ekranu \u2014 bez przerwy przy dolnej kraw\u0119dzi
  const STEP = 4; // odst\u0119p mi\u0119dzy liniami

  // Kamera / perspektywa \u2014 stan na g\u00f3rze strony (scroll = 0)
  // Wysoka kamera mocno pochylona w d\u00f3\u0142 (~40\u00b0) -> patrzymy na pod\u0142og\u0119 pod
  // k\u0105tem, wida\u0107 du\u017co powierzchni siatki, mocna perspektywa.
  const CAM_HEIGHT = 14; // wysoko\u015b\u0107 kamery nad pod\u0142og\u0105
  const CAM_Z = 10; // jak daleko kamera stoi od kraw\u0119dzi siatki
  const CAM_LOOK_Z = -7; // punkt na pod\u0142odze, na kt\u00f3ry patrzy kamera (steruje k\u0105tem)
  const FOV = 90; // bardzo szerokie pole widzenia -> wi\u0119ksza, mocniejsza perspektywa

  // Stan na dole strony (scroll = 1): kamera ni\u017cej + p\u0142aszczej (siatka „opada")
  const CAM_HEIGHT_END = 5; // obni\u017cenie kamery -> mniej z g\u00f3ry, bardziej grazing
  const CAM_LOOK_Z_END = -34; // patrzy dalej -> horyzont w g\u00f3r\u0119, p\u0142aszcza perspektyw\u0119

  const lerp = (a: number, b: number, t: number) => a + (b - a) * t;

  // Mg\u0142a -> rozp\u0142ywanie linii w g\u0142\u0119bi (kolor = t\u0142o strony)
  const FOG_NEAR = 18;
  const FOG_FAR = 150;

  let canvasEl: HTMLCanvasElement;

  onMount(() => {
    const renderer = new THREE.WebGLRenderer({
      canvas: canvasEl,
      alpha: true,
      antialias: true,
    });
    renderer.setClearColor(0x000000, 0);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    const scene = new THREE.Scene();
    scene.fog = new THREE.Fog(new THREE.Color(PAGE_BG), FOG_NEAR, FOG_FAR);

    const camera = new THREE.PerspectiveCamera(FOV, 1, 0.1, 1000);
    camera.position.set(0, CAM_HEIGHT, CAM_Z);
    camera.lookAt(0, 0, CAM_LOOK_Z);

    // Aktualizuje pochylenie/wysoko\u015b\u0107 kamery wg post\u0119pu scrolla (0..1).
    function applyScroll(progress: number) {
      const p = Math.min(Math.max(progress, 0), 1);
      camera.position.y = lerp(CAM_HEIGHT, CAM_HEIGHT_END, p);
      camera.lookAt(0, 0, lerp(CAM_LOOK_Z, CAM_LOOK_Z_END, p));
    }

    // --- Budowa siatki: linie wzd\u0142u\u017c X (poprzeczki) + wzd\u0142u\u017c Z (szyny) -------
    const positions: number[] = [];

    // Poprzeczki (r\u00f3wnoleg\u0142e do X) co STEP \u2014 od NEAR_Z (spod kamery) w g\u0142\u0105b
    for (let z = NEAR_Z; z >= -DEPTH; z -= STEP) {
      positions.push(-HALF_WIDTH, 0, z, HALF_WIDTH, 0, z);
    }
    // Szyny (r\u00f3wnoleg\u0142e do Z) co STEP w poziomie \u2014 od NEAR_Z do g\u0142\u0119bi
    for (let x = -HALF_WIDTH; x <= HALF_WIDTH; x += STEP) {
      positions.push(x, 0, NEAR_Z, x, 0, -DEPTH);
    }

    const geometry = new LineSegmentsGeometry();
    geometry.setPositions(positions);

    const material = new LineMaterial({
      color: new THREE.Color(LINE_COLOR),
      linewidth: LINE_WIDTH, // w pikselach (gdy worldUnits=false)
      worldUnits: false,
      transparent: true,
      opacity: LINE_OPACITY,
      fog: true,
      alphaToCoverage: true,
    });

    // --- Spotlight: poświata pod\u0105\u017caj\u0105ca za kursorem (liczona na GPU) -----------
    // Siatka jest w origin bez transformacji -> instanceStart/End == pozycja \u015bwiata.
    material.uniforms.uSpot = { value: new THREE.Vector3(0, 0, 1e6) };
    material.uniforms.uRadius = { value: SPOT_RADIUS };
    material.uniforms.uStrength = { value: 0 }; // 0 dop\u00f3ki kursor nieaktywny
    material.uniforms.uHotColor = { value: new THREE.Color(SPOT_COLOR) };

    material.onBeforeCompile = (shader) => {
      shader.vertexShader = shader.vertexShader
        .replace(
          "#include <clipping_planes_pars_vertex>",
          "#include <clipping_planes_pars_vertex>\n\t\tvarying vec3 vWorldPos;",
        )
        .replace(
          "gl_Position = clip;",
          "vWorldPos = ( position.y < 0.5 ) ? instanceStart : instanceEnd;\n\t\t\tgl_Position = clip;",
        );
      shader.fragmentShader = shader.fragmentShader
        .replace(
          "#include <clipping_planes_pars_fragment>",
          "#include <clipping_planes_pars_fragment>\n\t\tuniform vec3 uSpot;\n\t\tuniform float uRadius;\n\t\tuniform float uStrength;\n\t\tuniform vec3 uHotColor;\n\t\tvarying vec3 vWorldPos;",
        )
        .replace(
          "gl_FragColor = vec4( diffuseColor.rgb, alpha );",
          "float _d = distance( vWorldPos.xz, uSpot.xz );\n\t\t\tfloat _glow = smoothstep( uRadius, 0.0, _d ) * uStrength;\n\t\t\tvec3 _rgb = mix( diffuseColor.rgb, uHotColor, _glow );\n\t\t\tfloat _a = alpha + ( 1.0 - alpha ) * _glow;\n\t\t\tgl_FragColor = vec4( _rgb, _a );",
        );
    };

    const grid = new LineSegments2(geometry, material);
    scene.add(grid);

    function render() {
      renderer.render(scene, camera);
    }

    function resize() {
      const w = canvasEl.clientWidth;
      const h = canvasEl.clientHeight;
      renderer.setSize(w, h, false);
      material.resolution.set(w, h); // wymagane przez grube linie (px)
      camera.aspect = w / h;
      camera.updateProjectionMatrix();
      render();
    }

    resize();
    window.addEventListener("resize", resize);

    // --- Sterowanie scrollem (sp\u00f3jne ze ScrollSmoother strony) ---------------
    gsap.registerPlugin(ScrollTrigger);
    let st: ScrollTrigger | null = null;

    function setupScrollTrigger() {
      st = ScrollTrigger.create({
        start: 0,
        end: "max",
        scrub: true,
        onUpdate: (self) => {
          applyScroll(self.progress);
          render();
        },
      });
      applyScroll(st.progress);
      render();
    }

    // ScrollSmoother tworzony jest w Layout.astro \u2014 poczekaj a\u017c b\u0119dzie gotowy,
    // \u017ceby ScrollTrigger u\u017cy\u0142 wyg\u0142adzonego scrolla.
    if (window.smoother) {
      setupScrollTrigger();
    } else {
      window.addEventListener("smootherReady", setupScrollTrigger, { once: true });
    }

    // --- Hover spotlight: rzut kursora na pod\u0142og\u0119 + mi\u0119kka p\u0119tla rAF -----------
    const prefersReduced = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    const raycaster = new THREE.Raycaster();
    const floorPlane = new THREE.Plane(new THREE.Vector3(0, 1, 0), 0);
    const ndc = new THREE.Vector2();
    const hit = new THREE.Vector3();

    const curSpot = new THREE.Vector3(0, 0, 1e6);
    const tgtSpot = new THREE.Vector3(0, 0, 1e6);
    let curStrength = 0;
    let tgtStrength = 0;
    let rafId = 0;
    let looping = false;

    function loop() {
      curStrength += (tgtStrength - curStrength) * 0.12;
      curSpot.lerp(tgtSpot, 0.18);
      material.uniforms.uStrength.value = curStrength;
      material.uniforms.uSpot.value.copy(curSpot);
      render();
      if (Math.abs(tgtStrength - curStrength) > 0.002 || curStrength > 0.002) {
        rafId = requestAnimationFrame(loop);
      } else {
        looping = false;
        curStrength = 0;
        material.uniforms.uStrength.value = 0;
        render();
      }
    }
    function ensureLoop() {
      if (!looping) {
        looping = true;
        rafId = requestAnimationFrame(loop);
      }
    }

    function onPointerMove(e: PointerEvent) {
      const r = canvasEl.getBoundingClientRect();
      const inside =
        e.clientX >= r.left &&
        e.clientX <= r.right &&
        e.clientY >= r.top &&
        e.clientY <= r.bottom;
      if (!inside) {
        tgtStrength = 0;
        ensureLoop();
        return;
      }
      ndc.x = ((e.clientX - r.left) / r.width) * 2 - 1;
      ndc.y = -((e.clientY - r.top) / r.height) * 2 + 1;
      raycaster.setFromCamera(ndc, camera);
      if (raycaster.ray.intersectPlane(floorPlane, hit)) {
        tgtSpot.copy(hit);
        tgtStrength = SPOT_STRENGTH;
        ensureLoop();
      }
    }

    if (!prefersReduced) {
      window.addEventListener("pointermove", onPointerMove, { passive: true });
    }

    return () => {
      window.removeEventListener("resize", resize);
      window.removeEventListener("smootherReady", setupScrollTrigger);
      window.removeEventListener("pointermove", onPointerMove);
      cancelAnimationFrame(rafId);
      st?.kill();
      geometry.dispose();
      material.dispose();
      renderer.dispose();
    };
  });
</script>

<canvas bind:this={canvasEl} class="grid-floor" aria-hidden="true"></canvas>

<style>
  .grid-floor {
    position: fixed;
    left: 0;
    right: 0;
    /* Pas wychodzi spod dolnej kraw\u0119dzi ekranu \u2014 najbli\u017csze rz\u0119dy siatki
       s\u0105 poza widokiem, wi\u0119c siatka „zaczyna si\u0119" ju\u017c poni\u017cej ekranu. */
    bottom: -18vh;
    width: 100%;
    height: 68vh;
    pointer-events: none;
    /* Za tre\u015bci\u0105/napisami, ale nad t\u0142em strony. UWAGA: nie u\u017cywa\u0107 z-index: -1,
       bo t\u0142o <body> maluje si\u0119 nad ujemnym z-indexem i zas\u0142ania siatk\u0119.
       z-index: 0 -> canvas nad t\u0142em body, a #smooth-wrapper (p\u00f3\u017aniej w DOM) nad canvasem. */
    z-index: 0;
  }
</style>
