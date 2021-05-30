 <template>
  <div>
    <header>
      <b-navbar toggleable="lg" type="dark" variant="dark" sticky>
        <router-link class="nav-link" :to="{ name: 'patients' }">Пациенты</router-link>
        <router-link class="nav-link" :to="{ name: 'doctors' }">Врачи</router-link>
        <router-link class="nav-link" :to="{ name: 'sensors' }">Датчики</router-link>

        <!-- <router-link class="nav-link" :to="{ name: 'availbaleprobes' }">AvailableProbes</router-link> -->
        <b-navbar-nav class="ml-auto">
          <b-button @click="logout" variant="outline-danger">
            Выход
          </b-button>
        </b-navbar-nav>
      </b-navbar>
    </header>

    
    <router-view></router-view>
    
  </div>
</template>

<script>
export default {
  methods: {
    logout: function() {
      this.$store.commit("logout");
    }
  }
}
</script>
