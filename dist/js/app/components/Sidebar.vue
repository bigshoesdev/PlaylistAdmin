<template lang="html">
  <section class="sidebar">
    <transition name="darkness">
      <div v-if="$store.state.sidebar.opened" @click="$store.state.sidebar.opened = false" class="bg-dark"></div>
    </transition>
    <transition name="side-slide">
      <div class="popup" v-if="$store.state.sidebar.opened">
        <header>
          <button @click="$store.state.sidebar.opened = false" class="close-pop">
            <i class="icon ion-md-close"></i>
          </button>
          <router-link to="/account" class="bottom-btn">
            עריכה
          </router-link>
          <div class="user">
            <div
              class="avatar bg-img"
              :style="{ backgroundImage: `url(${$store.state.user.img})` }"
            ></div>
            <div class="info">
              <p>{{ $store.state.user.gender == 'fem' ? 'אישה' : 'גבר' }}</p>
              <p>{{ $store.state.user.name }} {{ $store.state.user.surname }} {{ $store.state.user.age }}</p>
              <p v-if="$store.getters.hasSubs">מנוי</p>
              <p v-else>אין מנוי</p>
            </div>
          </div>
        </header>
        <div class="content">
          <ul dir="rtl">
            <li>
              <router-link to="/main">דף בית</router-link>
            </li>
            <li>
              <router-link to="/search">חיפוש משחק</router-link>
            </li>
            <li>
              <router-link to="/events"><span class="fugaz">FUN ZONE</span> באזורך</router-link>
            </li>
            <!-- <li>
              <router-link to="/video">השפעות המשחק</router-link>
            </li> -->
            <li>
              <router-link to="/mish">
                רשימת המשחקנים
              </router-link>
            </li>
            <li>
              <router-link to="/liked">המועדפים שלי</router-link>
            </li>
            <li>
              <router-link to="/notifications">התראות</router-link>
            </li>
            <li>
              <router-link to="/terms">תנאי שימוש</router-link>
            </li>
            <li>
              <router-link to="/about">אודות</router-link>
            </li>
            <li>
              <router-link to="/contact">צור קשר</router-link>
            </li>
          </ul>
        </div>
      </div>
    </transition>
  </section>
</template>

<script>
export default {
  computed: {

  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.sidebar {

  .bg-dark {
    width: 100%;
    height: 100%;
    position: fixed;
    left: 0;
    top: 0;
    background: rgba(0, 0, 0, 0.3);
    transition: opacity 0.3s ease;
    opacity: 1;
    z-index: 99;
    &.darkness-leave-to {
      opacity: 0;
    }
    &.darkness-enter {
      opacity: 0;
    }
  }
  .popup {
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    position: fixed;
    width: 320px;
    height: 100%;
    right: 0;
    top: 0;
    background: $clr-d-pink;
    z-index: 100;
    transition: transform 0.3s ease;
    &.side-slide-leave-to {
      transform: translateX(150%);
    }
    &.side-slide-enter {
      transform: translateX(150%);
    }

    .content {
    	background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjg3IiBoZWlnaHQ9IjUwMyIgdmlld0JveD0iMCAwIDI4NyA1MDMiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTQ0LjUyNTQgMjM0Ljg4NEM0NS40NzEgMjM2LjUwNyA0Ny4wODEgMjM2LjkzOCA0OC4zNjg5IDIzNy4yODNDNDkuNjU2OSAyMzcuNjI4IDUxLjAzMDUgMjM3LjY1NCA1MS42NTQxIDIzOS4xOUM1Mi42ODUzIDI0MC40OTQgNTIuMDIxIDI0MS42ODUgNTEuMzU2NiAyNDIuODc3QzUwLjY5MjIgMjQ0LjA2OCA0OS45NDIzIDI0NS41NzkgNTAuODg3OSAyNDcuMjAyQzUxLjgzMzUgMjQ4LjgyNSA1My40NDM1IDI0OS4yNTYgNTQuNzMxNSAyNDkuNjAyQzU2LjAxOTQgMjQ5Ljk0NyA1Ny4zOTMgMjQ5Ljk3MiA1OC4wMTY2IDI1MS41MDlDNTguNzI1OCAyNTIuNzI2IDU4LjA2MTUgMjUzLjkxOCA1Ny43MTkxIDI1NS4xOTVDNTcuMDU0NyAyNTYuMzg3IDU1Ljk4MjggMjU3LjgxMSA1Ni45Mjg0IDI1OS40MzRDNTcuNzg4NCAyNjEuMzc3IDU5LjQ4NCAyNjEuNDg5IDYwLjc3MiAyNjEuODM0QzYyLjA2IDI2Mi4xNzkgNjMuNDMzNSAyNjIuMjA0IDY0LjA1NzIgMjYzLjc0MUM2NS4wODg0IDI2NS4wNDUgNjQuNDI0IDI2Ni4yMzYgNjMuNzU5NiAyNjcuNDI3QzYzLjA5NTIgMjY4LjYxOSA2Mi4zNDUzIDI3MC4xMyA2My4yOTA5IDI3MS43NTNDNjQuMjM2NSAyNzMuMzc2IDY1Ljg0NjUgMjczLjgwNyA2Ny4xMzQ1IDI3NC4xNTJDNjguNDIyNSAyNzQuNDk3IDY5Ljc5NiAyNzQuNTIzIDcwLjQxOTcgMjc2LjA2TDcxLjE0OTMgMjc1LjkxM0M3MC4yMDM2IDI3NC4yOSA2OC41OTM3IDI3My44NTggNjcuMzA1NyAyNzMuNTEzQzY2LjAxNzcgMjczLjE2OCA2NC42NDQxIDI3My4xNDIgNjQuMDIwNSAyNzEuNjA2QzYyLjk4OTMgMjcwLjMwMiA2My42NTM2IDI2OS4xMTEgNjQuMzE4IDI2Ny45MTlDNjQuOTgyNCAyNjYuNzI4IDY1LjczMjQgMjY1LjIxNyA2NC43ODY3IDI2My41OTRDNjQuMjQ4NyAyNjEuNzM4IDYyLjYzODcgMjYxLjMwNyA2MC45NDMyIDI2MS4xOTVDNTkuNjU1MiAyNjAuODUgNTguMjgxNiAyNjAuODI0IDU3LjY1OCAyNTkuMjg3QzU3LjAzNDQgMjU3Ljc1MSA1Ny42OTg3IDI1Ni41NTkgNTguMzYzMSAyNTUuMzY4QzU5LjAyNzUgMjU0LjE3NiA1OS43Nzc0IDI1Mi42NjUgNTguODMxOCAyNTEuMDQzQzU3Ljg4NjIgMjQ5LjQyIDU2LjI3NjIgMjQ4Ljk4OCA1NC45ODgyIDI0OC42NDNDNTMuNzAwMiAyNDguMjk4IDUyLjMyNjcgMjQ4LjI3MiA1MS43MDMxIDI0Ni43MzZDNTEuMDc5NCAyNDUuMTk5IDUxLjMzNjIgMjQ0LjI0MSA1Mi4wMDA2IDI0My4wNDlDNTIuNjY1IDI0MS44NTggNTMuNDE0OSAyNDAuMzQ3IDUyLjQ2OTMgMjM4LjcyNEM1MS41MjM3IDIzNy4xMDEgNDkuOTEzNyAyMzYuNjcgNDguNjI1NyAyMzYuMzI1QzQ3LjMzNzcgMjM1Ljk4IDQ1Ljk2NDIgMjM1Ljk1NCA0NS4zNDA1IDIzNC40MTdDNDQuNjMxMyAyMzMuMiA0NS4yOTU3IDIzMi4wMDkgNDYuMDQ1NyAyMzAuNDk4QzQ2LjcxIDIyOS4zMDYgNDcuNDYgMjI3Ljc5NSA0Ni41MTQ0IDIyNi4xNzNMNDUuNzg0OCAyMjYuMzE5QzQ2LjQ5NCAyMjcuNTM3IDQ1LjgyOTYgMjI4LjcyOCA0NS4xNjUzIDIyOS45MkM0NC42NTE3IDIzMS44MzYgNDMuNTc5NyAyMzMuMjYxIDQ0LjUyNTQgMjM0Ljg4NFoiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMS4zMTciIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIvPjxwYXRoIGQ9Ik01OS44MjI3IDQ3Mi40NTRDNTguMDAwNCA0NzEuOTk5IDU2LjU2OCA0NzIuODUxIDU1LjQyMjIgNDczLjUzM0M1NC4yNzYzIDQ3NC4yMTUgNTMuMjk5NSA0NzUuMTgxIDUxLjc2MzYgNDc0LjU1NkM1MC4xMTA0IDQ3NC4zODUgNDkuNzIwNCA0NzMuMDc4IDQ5LjMzMDQgNDcxLjc3MUM0OC45NDA0IDQ3MC40NjMgNDguMzgxMyA0NjguODcyIDQ2LjU1ODkgNDY4LjQxN0M0NC43MzY2IDQ2Ny45NjIgNDMuMzA0MiA0NjguODE1IDQyLjE1ODMgNDY5LjQ5NkM0MS4wMTI0IDQ3MC4xNzggNDAuMDM1NyA0NzEuMTQ0IDM4LjQ5OTggNDcwLjUxOUMzNy4xMzMgNDcwLjE3OCAzNi43NDMgNDY4Ljg3MSAzNi4wNjY2IDQ2Ny43MzRDMzUuNjc2NiA0NjYuNDI3IDM1LjQwMzkgNDY0LjY2NSAzMy41ODE2IDQ2NC4yMUMzMS41OTAxIDQ2My40NzEgMzAuMzI2OCA0NjQuNjA3IDI5LjE4MSA0NjUuMjg5QzI4LjAzNTEgNDY1Ljk3MSAyNy4wNTgzIDQ2Ni45MzcgMjUuNTIyNCA0NjYuMzEyQzIzLjg2OTIgNDY2LjE0MSAyMy40NzkyIDQ2NC44MzQgMjMuMDg5MiA0NjMuNTI2QzIyLjY5OTIgNDYyLjIxOSAyMi4xNDAxIDQ2MC42MjggMjAuMzE3NyA0NjAuMTczQzE4LjQ5NTQgNDU5LjcxOCAxNy4wNjMgNDYwLjU3IDE1LjkxNzEgNDYxLjI1MkMxNC43NzEyIDQ2MS45MzQgMTMuNzk0NSA0NjIuOSAxMi4yNTg2IDQ2Mi4yNzVMMTEuODU0OCA0NjIuOUMxMy42NzcxIDQ2My4zNTUgMTUuMTA5NSA0NjIuNTAyIDE2LjI1NTQgNDYxLjgyMUMxNy40MDEyIDQ2MS4xMzkgMTguMzc4IDQ2MC4xNzMgMTkuOTEzOSA0NjAuNzk4QzIxLjU2NzEgNDYwLjk2OSAyMS45NTcxIDQ2Mi4yNzYgMjIuMzQ3MSA0NjMuNTgzQzIyLjczNzEgNDY0Ljg5IDIzLjI5NjIgNDY2LjQ4MiAyNS4xMTg2IDQ2Ni45MzdDMjYuODIzNiA0NjcuODQ2IDI4LjI1NiA0NjYuOTk0IDI5LjUxOTIgNDY1Ljg1N0MzMC42NjUxIDQ2NS4xNzYgMzEuNjQxOCA0NjQuMjEgMzMuMTc3NyA0NjQuODM1QzM0LjcxMzYgNDY1LjQ2IDM1LjEwMzYgNDY2Ljc2OCAzNS40OTM2IDQ2OC4wNzVDMzUuODgzNiA0NjkuMzgyIDM2LjQ0MjcgNDcwLjk3MyAzOC4yNjUxIDQ3MS40MjhDNDAuMDg3NCA0NzEuODgzIDQxLjUxOTggNDcxLjAzMSA0Mi42NjU3IDQ3MC4zNDlDNDMuODExNiA0NjkuNjY3IDQ0Ljc4ODMgNDY4LjcwMSA0Ni4zMjQyIDQ2OS4zMjZDNDcuODYwMSA0NjkuOTUyIDQ4LjM2NzQgNDcwLjgwNCA0OC43NTc0IDQ3Mi4xMTJDNDkuMTQ3NCA0NzMuNDE5IDQ5LjcwNjYgNDc1LjAxIDUxLjUyODkgNDc1LjQ2NUM1My4zNTEzIDQ3NS45MiA1NC43ODM2IDQ3NS4wNjggNTUuOTI5NSA0NzQuMzg2QzU3LjA3NTQgNDczLjcwNCA1OC4wNTIyIDQ3Mi43MzggNTkuNTg4IDQ3My4zNjNDNjAuOTU0OCA0NzMuNzA0IDYxLjM0NDggNDc1LjAxMiA2MS45MDM5IDQ3Ni42MDNDNjIuMjkzOSA0NzcuOTEgNjIuODUzIDQ3OS41MDIgNjQuNjc1NCA0NzkuOTU3TDY1LjA3OTIgNDc5LjMzMkM2My43MTI1IDQ3OC45OSA2My4zMjI1IDQ3Ny42ODMgNjIuOTMyNSA0NzYuMzc2QzYxLjkxNzcgNDc0LjY3MSA2MS42NDUxIDQ3Mi45MDkgNTkuODIyNyA0NzIuNDU0WiIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIxLjMxNyIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIi8+PHBhdGggZD0iTTI3Ny4wNzEgNDAyQzI3NS43NDggNDAzLjMzMyAyNzUuNzQ4IDQwNSAyNzUuNzQ4IDQwNi4zMzRDMjc1Ljc0OCA0MDcuNjY3IDI3Ni4wNzkgNDA5LjAwMSAyNzQuNzU2IDQxMC4wMDFDMjczLjc2NCA0MTEuMzM0IDI3Mi40NDEgNDExLjAwMSAyNzEuMTE4IDQxMC42NjdDMjY5Ljc5NSA0MTAuMzM0IDI2OC4xNDIgNDEwLjAwMSAyNjYuODE5IDQxMS4zMzRDMjY1LjQ5NiA0MTIuNjY3IDI2NS40OTYgNDE0LjMzNCAyNjUuNDk2IDQxNS42NjhDMjY1LjQ5NiA0MTcuMDAxIDI2NS44MjcgNDE4LjMzNCAyNjQuNTA0IDQxOS4zMzVDMjYzLjUxMiA0MjAuMzM1IDI2Mi4xODkgNDIwLjAwMSAyNjAuODY2IDQyMC4wMDFDMjU5LjU0MyA0MTkuNjY4IDI1Ny44OSA0MTkuMDAxIDI1Ni41NjcgNDIwLjMzNUMyNTQuOTEzIDQyMS42NjggMjU1LjI0NCA0MjMuMzM1IDI1NS4yNDQgNDI0LjY2OEMyNTUuMjQ0IDQyNi4wMDIgMjU1LjU3NSA0MjcuMzM1IDI1NC4yNTIgNDI4LjMzNUMyNTMuMjYgNDI5LjY2OSAyNTEuOTM3IDQyOS4zMzUgMjUwLjYxNCA0MjkuMDAyQzI0OS4yOTEgNDI4LjY2OSAyNDcuNjM4IDQyOC4zMzUgMjQ2LjMxNSA0MjkuNjY5QzI0NC45OTIgNDMxLjAwMiAyNDQuOTkyIDQzMi42NjkgMjQ0Ljk5MiA0MzQuMDAyQzI0NC45OTIgNDM1LjMzNiAyNDUuMzIzIDQzNi42NjkgMjQ0IDQzNy42NjlMMjQ0LjMzMSA0MzguMzM2QzI0NS42NTQgNDM3LjAwMiAyNDUuNjU0IDQzNS4zMzYgMjQ1LjY1NCA0MzQuMDAyQzI0NS42NTQgNDMyLjY2OSAyNDUuMzIzIDQzMS4zMzUgMjQ2LjY0NiA0MzAuMzM1QzI0Ny42MzggNDI5LjAwMiAyNDguOTYxIDQyOS4zMzUgMjUwLjI4MyA0MjkuNjY5QzI1MS42MDYgNDMwLjAwMiAyNTMuMjYgNDMwLjMzNSAyNTQuNTgzIDQyOS4wMDJDMjU2LjIzNiA0MjguMDAyIDI1Ni4yMzYgNDI2LjMzNSAyNTUuOTA2IDQyNC42NjhDMjU1LjkwNiA0MjMuMzM1IDI1NS41NzUgNDIyLjAwMSAyNTYuODk4IDQyMS4wMDFDMjU4LjIyIDQyMC4wMDEgMjU5LjU0MyA0MjAuMzM1IDI2MC44NjYgNDIwLjY2OEMyNjIuMTg5IDQyMS4wMDEgMjYzLjg0MyA0MjEuMzM1IDI2NS4xNjUgNDIwLjAwMUMyNjYuNDg4IDQxOC42NjggMjY2LjQ4OCA0MTcuMDAxIDI2Ni40ODggNDE1LjY2OEMyNjYuNDg4IDQxNC4zMzQgMjY2LjE1NyA0MTMuMDAxIDI2Ny40OCA0MTIuMDAxQzI2OC44MDMgNDExLjAwMSAyNjkuNzk1IDQxMS4wMDEgMjcxLjExOCA0MTEuMzM0QzI3Mi40NDEgNDExLjY2NyAyNzQuMDk0IDQxMi4wMDEgMjc1LjQxNyA0MTAuNjY3QzI3Ni43NCA0MDkuMzM0IDI3Ni43NCA0MDcuNjY3IDI3Ni43NCA0MDYuMzM0QzI3Ni43NCA0MDUgMjc2LjQwOSA0MDMuNjY3IDI3Ny43MzIgNDAyLjY2N0MyNzguNzI0IDQwMS42NjcgMjgwLjA0NyA0MDIgMjgxLjcwMSA0MDIuMzMzQzI4My4wMjQgNDAyLjY2NyAyODQuNjc3IDQwMyAyODYgNDAxLjY2N0wyODUuNjY5IDQwMUMyODQuNjc3IDQwMiAyODMuMzU0IDQwMS42NjcgMjgyLjAzMSA0MDEuMzMzQzI4MC4wNDcgNDAxLjMzMyAyNzguMzk0IDQwMC42NjcgMjc3LjA3MSA0MDJaIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjEuMzE3IiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiLz48Y2lyY2xlIGN4PSI2LjUiIGN5PSI0MTQuNSIgcj0iNi41IiBmaWxsPSJ3aGl0ZSIvPjxwYXRoIGQ9Ik0zOS40NzQgNzEuNjI1Mkw0Ny45MDQxIDcyLjU5MTdNNDQuMTcyMyA2Ny44OTM1TDQzLjIwNTggNzYuMzIzNSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiLz48cGF0aCBkPSJNMTIwIDQ5Ni43MzJMMTI4LjQzIDQ5Ny42OThNMTI0LjY5OCA0OTNMMTIzLjczMiA1MDEuNDMiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIi8+PHBhdGggZD0iTTExNCA1LjczMTY5TDEyMi40MyA2LjY5ODE5TTExOC42OTggMS45OTk5MUwxMTcuNzMyIDEwLjQzIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIvPjwvc3ZnPg==');
      background-position: center bottom 30px;
      background-size: 90% auto;
      background-repeat: no-repeat;
      padding: 10px;
      padding-top: calc(131px + env(safe-area-inset-top));
      height: 100%;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      justify-content: flex-start;
      ul {
        text-align: right;
        margin: 0;
        padding: 30px 0;
        li {
          padding: 2px;
          a, button {
            font-size: 22px;
            color: #fff;
          }
        }
      }
    }

    header {
      width: 100%;
      position: absolute;
      top: 0;
      right: 0;
      padding: 30px 15px;
      padding-top: calc(30px + env(safe-area-inset-top));
      background: $clr-pink;
      border-bottom: 1px solid #fff;
      height: calc(131px + env(safe-area-inset-top));

      .close-pop {
        padding: 0;
        position: absolute;
        top: calc(15px + env(safe-area-inset-top));;
        left: 10px;
        width: 30px;
        height: 30px;
        font-size: 26px;
        color: #fff;
        z-index: 1;
      }
      .bottom-btn {
        display: block;
        position: absolute;
        bottom: 10px;
        left: 10px;
        height: 26px;
        border-radius: 13px;
        padding: 0 10px;
        background: $clr-red;
        border: 1px solid #fff;
        color: #fff;
        line-height: 26px;
        z-index: 10;
      }
      .user {
        direction: rtl;
        position: relative;
        .info {
          margin-right: 80px;
          p {
            margin: 0;
            line-height: 24px;
            color: #fff;
          }
        }
        .avatar {
          float: right;
          width: 70px;
          height: 70px;
          border-radius: 35px;
          border: 1px solid #fff;
          display: block;
        }
      }
    }
  }
}

</style>
