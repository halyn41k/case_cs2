<template>
  <header>
    <router-link to="/" class="logo">
      <img
        src="@/assets/cs2.png"
        alt="Logo"
      >
      <h1>Case CS2</h1>
    </router-link>
    <nav>
      <span class="nav-space"></span>
      <router-link to="/management">
        Керування
      </router-link>
      <span class="nav-space"></span>
      <router-link to="/add_account">
        Акаунти
      </router-link>
    </nav>
  </header>
</template>
  
<script>
export default {
  name: 'AppHeader'
};
</script>
  
<style scoped>
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #ccc;
  margin-bottom: 20px;
}

.logo {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: inherit;
}

.logo img {
  height: 40px;
  margin-right: 10px;
}

.logo h1 {
  margin: 0;
  font-size: 24px;
  color: inherit;
}

nav {
  display: flex;
  align-items: center;
}

nav a {
  text-decoration: none;
  color: #333;
  font-size: 18px;
}

nav a:hover {
  color: #FF9918;
}

.nav-space {
  width: 20px; /* Adjust the width to the desired space */
}
</style>
