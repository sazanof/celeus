<template>
    <div class="login">
        <div class="login-form">
            <img src="../../images/vorkfork3.svg">
            <div class="form-group">
                <VfInput
                    type="text"
                    :icon="true"
                    :placeholder="$t('Username')"
                    :value="username"
                    @on-change="username = $event.target.value">
                    <template #icon>
                        <Account :size="20" />
                    </template>
                </VfInput>
            </div>
            <div class="form-group">
                <VfInput
                    type="password"
                    :icon="true"
                    :placeholder="$t('Password')"
                    :value="password"
                    @on-change="password = $event.target.value">
                    <template #icon>
                        <Key :size="20" />
                    </template>
                </VfInput>
            </div>
            <div class="form-group">
                <VfButton
                    class="centered full"
                    @click="logIn">
                    {{ $t('Log in') }}
                </VfButton>
            </div>
        </div>
        <div class="forgot-password">
            <a
                @click="forgotPassword"
                href="#">{{ $t('Forgot password?') }}</a>
        </div>
    </div>
</template>

<script>
    import VfInput from '../elements/VfInput.vue'
    import VfButton from '../elements/VfButton.vue'
    import Account from 'vue-material-design-icons/Account.vue'
    import Key from 'vue-material-design-icons/Key.vue'

    export default {
        name: 'Login',
        components: {
            VfButton,
            VfInput,
            Account,
            Key
        },
        data() {
            return {
                username: '',
                password: ''
            }
        },
        methods: {
            logIn() {
                this.$store.dispatch('logIn', {
                    username: this.username,
                    password: this.password
                })
            },
            forgotPassword() {
                console.log('Forgot password form')
            }
        }
    }
</script>

<style lang="scss" scoped>
.login {
  position: absolute;
  z-index: 10;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: var(--color-primary);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  flex-direction: column;

  img {
    width: 100%;
    display: block;
    margin: 0 auto 28px auto;
  }

  .login-form {
    padding: calc(1.5 * var(--padding-box)) calc(2.5 * var(--padding-box));
    background: var(--color-white);
    border-radius: var(--border-radius-big);
    max-width: 400px;
    width: 100%;
  }

  .forgot-password {
    margin-top: 16px;

    a {
      color: var(--color-white-opacity50);
      text-decoration: none;

      &:hover {
        color: var(--color-white)
      }
    }
  }
}


</style>