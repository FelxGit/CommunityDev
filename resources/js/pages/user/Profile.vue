<template>
  <section class="vh-100">
  <div class="py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-12 col-xl-4">

        <div class="card">
          <div class="card-body text-center">
            <div class="profile">
              <img src="../../../assets/icons/user-logo.png" alt="Chronostep Community"
                class="rounded-circle img-fluid" style="width: 100px;" />
            </div>
            <h4 class="mb-2">{{ 'Fel Reind Y. Entica' }}</h4>
            <p class="text-muted mb-4">{{ '@Programmer' }}<span class="mx-2">|</span> <a
                href="#!">{{ 'Chronostep inc.' }}</a></p>
            <div class="social">
              <button type="button" class="btn btn-outline-primary btn-floating">
                <i class="fab fa-facebook-f fa-lg"></i>
              </button>
              <button type="button" class="btn btn-outline-primary btn-floating">
                <i class="fab fa-twitter fa-lg"></i>
              </button>
              <button type="button" class="btn btn-outline-primary btn-floating">
                <i class="fab fa-skype fa-lg"></i>
              </button>
            </div>
            <button type="button" class="message-btn btn btn-rounded btn-lg">
              {{ lang.get('words.MessageNow') }}
            </button>
            <div class="d-flex justify-content-between text-center mt-5 mb-2">
              <div>
                <p class="mb-2 h5">8471</p>
                <p class="text-muted mb-0">Wallets Balance</p>
              </div>
              <div class="px-3">
                <p class="mb-2 h5">8512</p>
                <p class="text-muted mb-0">Income amounts</p>
              </div>
              <div>
                <p class="mb-2 h5">4751</p>
                <p class="text-muted mb-0">Total Transactions</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
</template>
<script>
import { getters, mutations, actions } from "../../store";

export default {
  data() {
    return {
      //
    };
  },
  mounted() {
    console.log('mounted', this.user)
  },
  computed: {
    ...getters
  }
}
</script>

<style scoped lang="scss">
@import "../../../sass/imports";

.card {
  background-color: $brand-theme-color-secondary;
  .profile {
    margin: $base-gap;

    img {
      margin: auto
    }
  }
  .social {
    display: flex;
    justify-content: center;
    grid-gap: $base-gap;
    margin: $base-gap
  }

  .message-btn {
    background-color: $brand-primary;
    color: white
  }
}
</style>