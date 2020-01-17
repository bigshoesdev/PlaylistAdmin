<template lang="html">
  <div class="wrapper">
    <p class="bottom-text" dir="rtl">
      בכל לחיצה נקבע מי הבא/ה לענות
    </p>
    <div class="spinner" ref="parent" @click="rotate">
      <div v-if="mounted" class="names">
        <div v-for="(name, i) in names" :style="getWordPoint(i)" class="name">
          {{ name }}
        </div>
      </div>
      <svg class="polygons" v-if="mounted">
        <polygon v-for="(_, n) in Array(num)" :points="getPoints(n)" :fill="getColor(n)"/>
      </svg>
      <svg class="arrow" :style="{ transform: `rotate(${rotation}deg)` }" viewBox="0 0 65 68" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g filter="url(#filter0_d)">
          <path d="M19.7341 6.20903L33.5124 19.7562C36.3891 16.952 43.7244 10.1344 50.0519 5.29775C58.8548 3.04534 60.0354 9.53236 59.5254 13.0574C55.658 20.9645 46.6486 40.6473 41.6051 52.2412C38.2045 60.0587 30.9221 59.4701 27.7319 52.8305C23.5517 44.1301 13.9181 24.65 8.88007 12.4521C8.63525 3.06707 16.0141 4.37963 19.7341 6.20903Z" fill="white"/>
        </g>
        <defs>
          <filter id="filter0_d" x="0.560303" y="0.507324" width="63.7226" height="67.4814" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
            <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
            <feOffset dy="4"/>
            <feGaussianBlur stdDeviation="2"/>
            <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
          </filter>
        </defs>
      </svg>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    num: {
      type: [ String, Number ],
    },
    names: {
      type: Array,
    },
  },
  data() {
    return {
      center: [],
      radius: 0,
      mounted: false,
      interval: null,
      rotation: 0,
      colors: [
        '#EF8787',
        '#D073E8',
        '#8E91EB',
        '#E7EF87',
        '#CEE7EF',
        '#73E878',
        '#EFB987',
        '#73E1E8',
      ],
    };
  },
  methods: {
    rotate() {
      this.rotation = this.rotation + (360 * Math.random()) + (360 * 10);
    },
    getColor(n) {
      return this.colors[n % this.colors.length];
    },
    getCoords(n) {
      var deg = this.degreeChunk * n;
      var half_deg = this.degreeChunk / 2;

      return [
        [
          this.center[0] + (this.radius * Math.sin(deg - half_deg)),
          this.center[1] + (this.radius * Math.cos(deg - half_deg)),
        ],
        [
          this.center[0] + (this.radius * Math.sin(deg - (half_deg / 2))),
          this.center[1] + (this.radius * Math.cos(deg - (half_deg / 2))),
        ],
        [
          this.center[0] + (this.radius * Math.sin(deg + (half_deg / 2))),
          this.center[1] + (this.radius * Math.cos(deg + (half_deg / 2))),
        ],
        [
          this.center[0] + (this.radius * Math.sin(deg + half_deg)),
          this.center[1] + (this.radius * Math.cos(deg + half_deg)),
        ]
      ];
    },
    getWordPoint(n) {
      var deg = this.degreeChunk * n;

      return {
        left: (this.center[0] + (80 * Math.sin(deg))) + 'px',
        top: (this.center[1] + (80 * Math.cos(deg))) + 'px',
      };
    },
    getPoints(n) {

      var points = this.getCoords(n).map(z => {
        return ` ${z[0]},${z[1]}`;
      });

      return `${this.center[0]},${this.center[1]}${points}`;
    }
  },
  computed: {
    degreeChunk() {
      return (Math.PI * 2) / this.num;
    }
  },
  mounted() {
    this.radius = (this.$refs.parent.clientWidth / 2) * 2;
    this.center = [
      this.$refs.parent.clientWidth / 2,
      this.$refs.parent.clientHeight / 2,
    ];
    this.mounted = true;
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.wrapper {
  padding: 5px 15px;
  padding-bottom: 20px;
  .bottom-text {
    text-align: center;
    margin: 0;
    @media (max-width: 370px) {
      font-size: 15px;
    }
  }
}
.names {
  .name {
    position: absolute;
    transform: translate(-50%, -50%);
    color: #005D7A;
  }
}
.spinner {
  position: relative;
  border: 2px solid #fff;
  height: 230px;
  cursor: pointer;
  background: #fff;

  .polygons {
    width: 100%;
    height: 100%;
  }
  .arrow {
    transition: transform 2s ease;
    width: 68px;
    height: 68px;
    position: absolute;
    left: 50%;
    margin-left: -34px;
    top: 50%;
    margin-top: -34px;
  }
}

</style>
