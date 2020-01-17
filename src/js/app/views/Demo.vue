<template lang="html">
  <main ref="main">

  </main>
</template>

<script>
import * as THREE from 'three';
import PointerLockControls from './../astro/controls.js';
import SIM from './../astro';

export default {
  data() {
    return {
      agent: null,
      controls: null,
    };
  },
  methods: {
    onKeydown(e) {

      const k = 0.01;

      console.log(e);

      if(e.key == 'w') {
        var vec = new THREE.Vector3(0, 0, 0);
        this.controls.getDirection(vec);
        vec.multiplyScalar(0.01);
        console.log(vec);
        SIM.objects[0].speed[0] += vec.z;
        SIM.objects[0].speed[1] += vec.x;
        SIM.objects[0].speed[2] += vec.y;

      }

    }
  },
  mounted() {

    var scene = new THREE.Scene();
    var camera = new THREE.PerspectiveCamera( 70, window.innerWidth / window.innerHeight, 0.1, 1000 );
    this.camera = camera;

    var light = new THREE.PointLight( 0xffffff, 2, 1000 );
    scene.add( light );

    const direction = new THREE.Vector3();
    const axesHelper = new THREE.AxesHelper( 5 );

    var renderer = new THREE.WebGLRenderer();
    renderer.setPixelRatio( window.devicePixelRatio );
    renderer.setSize( window.innerWidth, window.innerHeight );
    this.$refs.main.appendChild( renderer.domElement );

    const controls = new PointerLockControls(camera, renderer.domElement);
    renderer.domElement.addEventListener('click', () => controls.lock());
    this.controls = controls;

    SIM.init(scene);

    this.agent = new SIM.Unit('agent', 0.1, 30, new THREE.MeshBasicMaterial({
      color: 0xff0000,
      metalness: 1,
      roughness: 0.80,
    }), [
      5, 20, 20
    ]);

    this.agent.mesh.add(controls.getObject());

    SIM.add(this.agent);

    var loader = new THREE.TextureLoader();
    loader.load('/static/marssurface.jpg', function ( texture ) {
      SIM.add(new SIM.Unit('sun', 10, 300000000, new THREE.MeshStandardMaterial({
        color: 0xffffff,
        metalness: 1,
        roughness: 0.80,
        map: texture,
      })));
    });



    SIM.add(new SIM.Unit('test-2', 0.2, 30000, new THREE.MeshStandardMaterial({
      color: 0xffffff,
      metalness: 1,
      roughness: 0.80,
    }), [
      1, 1, 15
    ]));

    SIM.add(new SIM.Unit('test-3', 0.5, 3000, new THREE.MeshStandardMaterial({
      color: 0xffffff,
      metalness: 1,
      roughness: 0.80,
    }), [
      2, 15, 1
    ]));

    SIM.objects[0].mesh.add(axesHelper);

		var animate = () => {

			requestAnimationFrame( animate );

      SIM.tick();
      // controls.update();
      light.position.copy(SIM.objects[0].mesh.position);

			renderer.render(scene, camera);
		};

		animate();
    document.addEventListener('keydown', this.onKeydown);
  }
}
</script>

<style lang="scss" scoped>



</style>
