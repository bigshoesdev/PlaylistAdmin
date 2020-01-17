<template>
  <div class="player">
    <div class="play-button">
      <a v-on:click.prevent="playing = !playing" title="Play/Pause" href="#">
        <img v-if="playing" src="/img/pause.svg"/>
        <img v-else src="/img/play.svg"/>
      </a>
    </div>
    <!--div>
      <div v-on:click="seek" class="player-progress" title="Time played : Total time">
        <div :style="{ width: this.percentComplete + '%' }" class="player-seeker"></div>
      </div>
      <div class="player-time">
        <div class="player-time-current">{{ currentTime }}</div>
        <div class="player-time-total">{{ durationTime }}</div>
      </div>
    </div-->
    <audio ref="audiofile" :src="file" preload="auto" style="display: none;"></audio>
  </div>
</template>

<script>
const convertTimeHHMMSS = (val) => {
  let hhmmss = new Date(val * 1000).toISOString().substr(11, 8);
  return hhmmss.indexOf("00:") === 0 ? hhmmss.substr(3) : hhmmss;
};

export default {
  props: {
    file: {
      type: String,
      default: null,
    }
  },
  data: () => ({
    audio: undefined,
    currentSeconds: 0,
    durationSeconds: 0,
    innerLoop: false,
    loaded: false,
    playing: false,
    volume: 100
  }),
  computed: {
    currentTime() {
      return convertTimeHHMMSS(this.currentSeconds);
    },
    durationTime() {
      return convertTimeHHMMSS(this.durationSeconds);
    },
    percentComplete() {
      return parseInt(this.currentSeconds / this.durationSeconds * 100);
    }
  },
  watch: {
    playing(value) {
      if (value) {
        return this.audio.play();
      }
      this.audio.pause();
    }
  },
  methods: {
    load() {
      if (this.audio.readyState >= 2) {
        this.loaded = true;
        this.durationSeconds = parseInt(this.audio.duration);
        return false;
      }
      throw new Error('Failed to load sound file.');
    },
    seek(e) {
      if (!this.playing || e.target.tagName === 'SPAN') {
        return;
      }
      const el = e.target.getBoundingClientRect();
      const seekPos = (e.clientX - el.left) / el.width;
      this.audio.currentTime = parseInt(this.audio.duration * seekPos);
    },
    stop() {
      this.playing = false;
      this.audio.currentTime = 0;
    },
    update(e) {
      this.currentSeconds = parseInt(this.audio.currentTime);
    }
  },
  created() {
    this.innerLoop = this.loop;
  },
  mounted() {
    this.audio = this.$el.querySelectorAll('audio')[0];
    this.audio.addEventListener('timeupdate', this.update);
    this.audio.addEventListener('loadeddata', this.load);
    this.audio.addEventListener('pause', () => { this.playing = false; });
    this.audio.addEventListener('play', () => { this.playing = true; });
  }
}
</script>

<style lang="scss" scoped>
.player {
  .play-button {
    /*width: 3rem;*/
    a {
      /*border: 3px solid #7E9CC2;*/
      /*border-radius: 50%;*/
      width: 3rem;
      height: 3rem;
      display: flex;
      justify-content: center;
      align-items: center;
      img {
        width: 2rem;
        height: 2rem;
      }
    }
  }
}
</style>
