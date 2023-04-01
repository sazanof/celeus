<template>
    <div class="logo">
        <a href="/">
            <img src="../../images/vorkfork.svg">
        </a>
    </div>
</template>

<script>
    export default {
        name: 'Logo'
    }
</script>

<style lang="scss" scoped>
.logo {
  width: var(--sidebar-width);

  img {
	max-width: 60px;
	width: 90%;
	margin: 0 auto;
	display: block;
  }
}
</style>