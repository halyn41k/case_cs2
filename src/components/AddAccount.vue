<template>
  <div>
    <h2>Додати акаунт</h2>
    <form @submit.prevent="addAccount">
      <div>
        <label for="account_name">Назва акаунту:</label>
        <input type="text" v-model="account_name" required />
      </div>
      <div>
        <label for="email">Електронна пошта:</label>
        <input type="email" v-model="email" required />
      </div>
      <div>
        <label for="password">Пароль:</label>
        <input type="password" v-model="password" required />
      </div>
      <button type="submit">Додати акаунт</button>
    </form>
    <p v-if="error">{{ error }}</p>
    <p v-if="successMessage">{{ successMessage }}</p>
    <h2>Список акаунтів</h2>
    <table v-if="accounts.length">
      <thead>
        <tr>
          <th>ID</th>
          <th>Назва акаунту</th>
          <th>Електронна пошта</th>
          <th>Пароль</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="account in accounts" :key="account.id">
          <td>{{ account.id }}</td>
          <td>{{ account.name }}</td>
          <td>{{ account.email }}</td>
          <td>{{ account.password }}</td>
        </tr>
      </tbody>
    </table>
    <p v-else>No accounts available</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      account_name: '',
      email: '',
      password: '',
      accounts: [],
      error: null,
      successMessage: null
    };
  },
  created() {
    this.fetchAccounts();
  },
  methods: {
    fetchAccounts() {
      fetch('http://localhost/case-cs2/backend/server.php?fetch=accounts')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            this.accounts = data.accounts;
          } else {
            this.error = data.error;
          }
        })
        .catch(error => {
          this.error = `Failed to fetch accounts: ${error.message}`;
        });
    },
    addAccount() {
      const data = {
        account_name: this.account_name,
        email: this.email,
        password: this.password
      };
      fetch('http://localhost/case-cs2/backend/server.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          this.successMessage = data.message;
          this.account_name = '';
          this.email = '';
          this.password = '';
          this.fetchAccounts();
        } else {
          this.error = data.error;
        }
      })
      .catch(error => {
        this.error = `Failed to add account: ${error.message}`;
      });
    }
  }
};
</script>

<style scoped>
form {
  display: flex;
  flex-direction: column;
}

div {
  margin-bottom: 10px;
}

label,
input {
  display: block;
  width: 100%;
  max-width: 300px;
}

input {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-top: 5px;
  box-sizing: border-box;
}

button {
  font-family: inherit; /* Ensure button uses the same font as the text */
  padding: 10px 20px;
  border: none;
  color: #fff;
  cursor: pointer;
  border-radius: 4px;
}

button:hover {
  background-color: #FF9918;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 10px;
  border: 1px solid #ccc;
  text-align: left;
}
</style>
