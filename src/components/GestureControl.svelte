<script lang="ts">
  import { onMount } from "svelte";
  import * as THREE from "three";

  let handCanvasEl = $state<HTMLCanvasElement>();
  let isHovered = $state(false);

  onMount(() => {
    let hRenderer: THREE.WebGLRenderer | null = null;
    let hScene: THREE.Scene | null = null;
    let hCamera: THREE.OrthographicCamera | null = null;
    let armGroup: THREE.Group | null = null;

    let sleeveGeo: THREE.BoxGeometry | null = null;
    let handGeo: THREE.BoxGeometry | null = null;
    let thumbGeo: THREE.BoxGeometry | null = null;
    let finger1Geo: THREE.BoxGeometry | null = null;
    let finger2Geo: THREE.BoxGeometry | null = null;
    let finger3Geo: THREE.BoxGeometry | null = null;

    let sleeveMat: THREE.MeshBasicMaterial | null = null;
    let handMat: THREE.MeshBasicMaterial | null = null;
    let jointMat: THREE.MeshBasicMaterial | null = null;

    let hRaf = 0;

    if (handCanvasEl) {
      hRenderer = new THREE.WebGLRenderer({
        canvas: handCanvasEl,
        alpha: true,
        antialias: false,
      });
      hRenderer.setSize(96, 96);
      hRenderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

      hScene = new THREE.Scene();

      hCamera = new THREE.OrthographicCamera(-1.8, 1.8, 1.8, -1.8, 0.1, 100);
      hCamera.position.set(2.4, 2.8, 3.8);
      hCamera.lookAt(0, 0.8, 0);

      const ambientLight = new THREE.AmbientLight(0xffffff, 0.65);
      hScene.add(ambientLight);

      const dirLight = new THREE.DirectionalLight(0xffffff, 0.9);
      dirLight.position.set(2, 4, 3);
      hScene.add(dirLight);

      sleeveMat = new THREE.MeshBasicMaterial({ color: 0x010101, wireframe: true });
      handMat = new THREE.MeshBasicMaterial({ color: 0xFE5030, wireframe: true });
      jointMat = new THREE.MeshBasicMaterial({ color: 0xFF6D52, wireframe: true });

      armGroup = new THREE.Group();
      hScene.add(armGroup);

      sleeveGeo = new THREE.BoxGeometry(0.7, 1.1, 0.7);
      const sleeveMesh = new THREE.Mesh(sleeveGeo, sleeveMat);
      sleeveMesh.position.set(0, 0.55, 0);
      armGroup.add(sleeveMesh);

      handGeo = new THREE.BoxGeometry(0.8, 0.7, 0.6);
      const handMesh = new THREE.Mesh(handGeo, handMat);
      handMesh.position.set(0, 1.45, 0);
      armGroup.add(handMesh);

      thumbGeo = new THREE.BoxGeometry(0.24, 0.35, 0.24);
      const thumbMesh = new THREE.Mesh(thumbGeo, jointMat);
      thumbMesh.position.set(-0.48, 1.35, 0.1);
      armGroup.add(thumbMesh);

      finger1Geo = new THREE.BoxGeometry(0.24, 0.45, 0.24);
      const finger1 = new THREE.Mesh(finger1Geo, handMat);
      finger1.position.set(-0.25, 1.95, 0);
      armGroup.add(finger1);

      finger2Geo = new THREE.BoxGeometry(0.24, 0.5, 0.24);
      const finger2 = new THREE.Mesh(finger2Geo, jointMat);
      finger2.position.set(0.02, 1.98, 0);
      armGroup.add(finger2);

      finger3Geo = new THREE.BoxGeometry(0.24, 0.42, 0.24);
      const finger3 = new THREE.Mesh(finger3Geo, handMat);
      finger3.position.set(0.28, 1.94, 0);
      armGroup.add(finger3);

      armGroup.position.set(0, -0.65, 0);

      const hAnimate = () => {
        hRaf = requestAnimationFrame(hAnimate);
        const time = performance.now() * 0.001;

        if (armGroup && hRenderer && hScene && hCamera) {
          armGroup.rotation.z = Math.sin(time * 1.6) * 0.04;
          armGroup.rotation.x = 0;
          armGroup.position.y = -0.65 + Math.sin(time * 1.6) * 0.02;

          if (isHovered) {
            armGroup.rotation.y = time * 2.5;
            armGroup.rotation.z = Math.sin(time * 5) * 0.15;
          } else {
            armGroup.rotation.y = time * 0.65;
          }
          hRenderer.render(hScene, hCamera);
        }
      };
      hAnimate();
    }

    return () => {
      cancelAnimationFrame(hRaf);
      sleeveGeo?.dispose();
      handGeo?.dispose();
      thumbGeo?.dispose();
      finger1Geo?.dispose();
      finger2Geo?.dispose();
      finger3Geo?.dispose();
      sleeveMat?.dispose();
      handMat?.dispose();
      jointMat?.dispose();
      hRenderer?.dispose();
    };
  });
</script>

<div
  class="gc-hand-wrapper"
  role="presentation"
  onmouseenter={() => (isHovered = true)}
  onmouseleave={() => (isHovered = false)}
>
  <canvas bind:this={handCanvasEl} class="gc-hand-3d"></canvas>
</div>

<style>
  .gc-hand-wrapper {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: default;
    background: none;
    border: none;
    padding: 0;
  }

  .gc-hand-3d {
    width: 96px;
    height: 96px;
    display: block;
    image-rendering: pixelated;
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }
  .gc-hand-wrapper:hover .gc-hand-3d {
    transform: scale(1.1);
  }
</style>
