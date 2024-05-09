<template>
    <div class="section">
        <div class="section-wrapper">
            <div class="section-icon">
                <slot name="icon" />
            </div>
            <div class="section-inner">
                <div class="title">
                    {{ title }}
                    <div class="description">
                        {{ description }}
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <slot />
        </div>
    </div>
</template>
<script>
  // TODO make Actions in right of title
    export default {
        name: 'VfSection',
        props: {
            title: {
                type: String,
                default: ''
            },
            description: {
                type: String,
                default: ''
            }
        }
    }
</script>

<style lang="scss" scoped>

.section {

  &:not(&:first-child) {
    margin-top: 36px;
  }

  .section-wrapper {
    display: flex;
    align-items: center;
    font-weight: bold;
    margin-bottom: 20px;
    font-size: 20px;
    color: var(--color-primary);
    background: var(--color-background-light);
    padding: 8px;
    border-radius: var(--border-radius);

    .section-inner {
      padding-left: 10px;
    }

    .section-icon {
      color: rgba(0, 0, 0, 0.1);
    }
  }

  .title {

    .description {
      font-size: 14px;
      font-weight: normal;
      color: var(--color-text-light)
    }
  }

  .content {
    text-align: left;
  }
}
</style>
