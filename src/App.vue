<template>
  <div id="app">
    <AppHeader />
    <router-view />
  </div>
</template>

<script>
import AppHeader from './components/AppHeader.vue';

export default {
  components: {
    AppHeader
  }
};
</script>

<style>
body {
  font-family: 'PT Sans Narrow', sans-serif;
  background-color: white;
}

#app {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  font-size: 24px;
  margin-bottom: 20px;
}

form {
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 5px;
}

input[type="text"],
input[type="number"],
input[type="file"],
button {
  padding: 10px;
  margin-bottom: 20px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  background-color: #FF9918;
  color: white;
  border: none;
  cursor: pointer;
}

button:hover {
  background-color: #e68a00;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  margin-bottom: 10px;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  display: flex;
  align-items: center;
}

li img {
  max-width: 100px;
  margin-right: 20px;
}

p {
  font-size: 18px;
  font-weight: bold;
}
</style>
