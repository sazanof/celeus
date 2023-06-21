<template>
    <div class="search-messages">
        <VfInput
            :placeholder="$t('mail', 'Search messages')"
            :value="search"
            class="input" />
        <VfButton
            class="filter"
            type="transparent"
            :only-icon="true">
            <template #icon>
                <FilterCogIcon :size="20" />
            </template>
        </VfButton>
    </div>
</template>

<script>
    import VfButton from '../../../../../resources/components/elements/VfButton.vue'
    import VfInput from '../../../../../resources/components/elements/VfInput.vue'
    import FilterCogIcon from 'vue-material-design-icons/FilterCog.vue'

    export default {
        name: 'SearchMessages',
        components: {
            VfInput,
            VfButton,
            FilterCogIcon
        },
        data() {
            return {
                search: null
            }
        }
    }
</script>

<style lang="scss" scoped>
.search-messages {
  padding: var(--padding-box);
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: var(--border-width) solid var(--border-color);

  .input {
	width: 100%;
  }

  .filter {
	margin-left: 10px;

	.material-design-icon {

	  color: var(--color-primary)
	}
  }
}
</style>
