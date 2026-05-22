<script lang="ts">
  import { onMount } from "svelte";
  import * as THREE from "three";
  import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js";
  import CustomShaderMaterial from "three-custom-shader-material/vanilla";
  import { mergeVertices } from "three/addons/utils/BufferGeometryUtils.js";
  import { GUI } from "lil-gui";
  import wobbleVertexShader from "../shaders/wobble/vertex.glsl";
  import wobbleFragmentShader from "../shaders/wobble/fragment.glsl";

  let canvasEl: HTMLCanvasElement;

  onMount(() => {
    // ── Sizes ────────────────────────────────────────────────────────
    const sizes = {
      width:  canvasEl.parentElement?.clientWidth  ?? window.innerWidth,
      height: canvasEl.parentElement?.clientHeight ?? window.innerHeight,
    };

    // ── Renderer ─────────────────────────────────────────────────────
    const renderer = new THREE.WebGLRenderer({
      canvas: canvasEl,
      antialias: true,
      alpha: true,
    });
    renderer.setSize(sizes.width, sizes.height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.0;
    renderer.outputColorSpace = THREE.SRGBColorSpace;

    // ── Scene ────────────────────────────────────────────────────────
    const scene = new THREE.Scene();

    // ── Camera ───────────────────────────────────────────────────────
    const camera = new THREE.PerspectiveCamera(35, sizes.width / sizes.height, 0.1, 100);
    camera.position.set(0, 0, 5);
    scene.add(camera);

    // ── Controls ─────────────────────────────────────────────────────
    const controls = new OrbitControls(camera, canvasEl);
    controls.enableDamping = true;

    // ── Lights ───────────────────────────────────────────────────────
    const ambientLight = new THREE.AmbientLight("#ffffff", 1);
    scene.add(ambientLight);

    const dirLight = new THREE.DirectionalLight("#ffffff", 3);
    dirLight.position.set(1, 2, 3);
    scene.add(dirLight);

    // ── Geometry ─────────────────────────────────────────────────────
    let geometry = new THREE.IcosahedronGeometry(2.5, 50);
    geometry = mergeVertices(geometry);
    geometry.computeTangents();

    // ── GUI & Uniforms ───────────────────────────────────────────────
    const gui = new GUI({ width: 325 });

    const debugObject = {
      colorA: "#0000ff",
      colorB: "#ff0000",
    };

    const uniforms = {
      uTime:                  new THREE.Uniform(0),
      uPositionFrequency:     new THREE.Uniform(0.5),
      uTimeFrequency:         new THREE.Uniform(0.4),
      uStrength:              new THREE.Uniform(0.3),
      uWarpPositionFrequency: new THREE.Uniform(0.38),
      uWarpTimeFrequency:     new THREE.Uniform(0.12),
      uWarpStrength:          new THREE.Uniform(1.7),
      uColorA:                new THREE.Uniform(new THREE.Color(debugObject.colorA)),
      uColorB:                new THREE.Uniform(new THREE.Color(debugObject.colorB)),
    };

    gui.add(uniforms.uPositionFrequency, "value", 0, 2, 0.001).name("uPositionFrequency");
    gui.add(uniforms.uTimeFrequency, "value", 0, 2, 0.001).name("uTimeFrequency");
    gui.add(uniforms.uStrength, "value", 0, 2, 0.001).name("uStrength");
    gui.add(uniforms.uWarpPositionFrequency, "value", 0, 2, 0.001).name("uWarpPositionFrequency");
    gui.add(uniforms.uWarpTimeFrequency, "value", 0, 2, 0.001).name("uWarpTimeFrequency");
    gui.add(uniforms.uWarpStrength, "value", 0, 2, 0.001).name("uWarpStrength");
    gui.addColor(debugObject, "colorA").onChange(() => uniforms.uColorA.value.set(debugObject.colorA));
    gui.addColor(debugObject, "colorB").onChange(() => uniforms.uColorB.value.set(debugObject.colorB));

    // ── Material (CSM) ───────────────────────────────────────────────
    // Material
    const material = new CustomShaderMaterial({
      // CSM
      baseMaterial:   THREE.MeshPhysicalMaterial,
      vertexShader:   wobbleVertexShader,
      fragmentShader: wobbleFragmentShader,
      uniforms:       uniforms,

      // MeshPhysicalMaterial
      metalness:    0,
      roughness:    0.5,
      color:        "#ffffff",
      transmission: 0,
      ior:          1.5,
      thickness:    1.5,
      transparent:  true,
      wireframe:    false,
    });

    const depthMaterial = new CustomShaderMaterial({
      // CSM
      baseMaterial: THREE.MeshDepthMaterial,
      vertexShader: wobbleVertexShader,
      uniforms:     uniforms,

      // MeshDepthMaterial
      depthPacking: THREE.RGBADepthPacking,
    });

    // ── Mesh ─────────────────────────────────────────────────────────
    const mesh = new THREE.Mesh(geometry, material);
    mesh.customDepthMaterial = depthMaterial;
    scene.add(mesh);

    // ── Resize ───────────────────────────────────────────────────────
    const onResize = () => {
      sizes.width  = canvasEl.parentElement?.clientWidth  ?? window.innerWidth;
      sizes.height = canvasEl.parentElement?.clientHeight ?? window.innerHeight;

      camera.aspect = sizes.width / sizes.height;
      camera.updateProjectionMatrix();

      renderer.setSize(sizes.width, sizes.height);
      renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    };
    window.addEventListener("resize", onResize);

    // ── Clock & Loop ─────────────────────────────────────────────────
    const clock = new THREE.Clock();
    let raf: number;

    const tick = () => {
      const elapsed = clock.getElapsedTime();

      // Materials
      uniforms.uTime.value = elapsed;

      controls.update();
      renderer.render(scene, camera);
      raf = requestAnimationFrame(tick);
    };
    raf = requestAnimationFrame(tick);

    // ── Cleanup ──────────────────────────────────────────────────────
    return () => {
      cancelAnimationFrame(raf);
      window.removeEventListener("resize", onResize);
      controls.dispose();
      geometry.dispose();
      material.dispose();
      depthMaterial.dispose();
      renderer.dispose();
      gui.destroy();
    };
  });
</script>

<div class="scene-wrapper">
  <canvas bind:this={canvasEl}></canvas>
</div>

<style>
  .scene-wrapper {
    position: fixed;
    inset: 0;
    width: 100vw;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #1a1a1a;
    z-index: 0;
  }

  canvas {
    display: block;
    width: 100%;
    height: 100%;
  }
</style>
