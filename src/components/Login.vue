<template>
    <div class="login-container">
      <h1>Вхід</h1>
      <form @submit.prevent="login">
        <label for="email">Пошта</label>
        <input type="email" v-model="email" required />
  
        <label for="password">Пароль</label>
        <input type="password" v-model="password" required />
  
        <button type="submit">Увійти</button>
  
        <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
      </form>
    </div>
  </template>
  
  <script>
 export default {
  name: 'LoginPage',
  data() {
    return {
      email: '',
      password: '',
      errorMessage: ''
    };
  },
  methods: {
    async login() {
      const response = await fetch('http://localhost/case-cs2/backend/server.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          email: this.email,
          password: this.password
        })
      });
      const result = await response.json();

      if (result.success) {
        localStorage.setItem('isAuthenticated', true);
        this.$router.push('/home');
      } else {
        this.errorMessage = result.error;
      }
    }
  },
  mounted() {
    const isAuthenticated = localStorage.getItem('isAuthenticated');
    if (isAuthenticated) {
      this.$router.push('/home');
    }
  }
};

  </script>
  
  <style scoped>
  .login-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
  }
  
  h1 {
    text-align: center;
  }
  
  form {
    display: flex;
    flex-direction: column;
  }
  
  label {
    margin-top: 10px;
  }
  
  input {
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  button {
    margin-top: 20px;
    padding: 10px;
    background-color: #FF9918;
    border: none;
    color: white;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #e68a00;
  }
  
  .error {
    color: red;
    font-size: 14px;
    text-align: center;
  }
  </style>
  