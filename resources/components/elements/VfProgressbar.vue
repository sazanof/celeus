<template>
    <div class="progress">
        <div
            class="progress-percents"
            :style="`width: ${width}%`" />
    </div>
</template>

<script>
    export default {
        name: 'VfProgressbar',
        props: {
            width: {
                type: Number,
                default: 0,
            }
        },
    }
</script>

<style lang="scss" scoped>
.progress {
  background: var(--color-lighter);
  position: relative;
  height: 5px;
  border-radius: 10px;

  .progress-percents {
    transition: width var(--transition-duration);
    position: absolute;
    border-radius: 10px;
    height: 5px;
    left: 0;
    top: 0;
    background: var(--color-green);
    z-index: 2;
  }
}
</style>