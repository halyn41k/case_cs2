<template>
  <div>
    <h2>Список кейсів</h2>
    <div>
      <label>Сортування:</label>
      <button @click="sortCases('price', true)">Ціна (зростання)</button>
      <button @click="sortCases('price', false)">Ціна (спадання)</button>
    </div>
    <div>
      <label>Фільтрувати по місяцях:</label>
      <div><input type="month" v-model="filterMonth" @change="filterCases" /></div>
    </div>
    <div>
      <label>Фільтрувати по назві кейсу:</label>
      <div>
        <select v-model="filterCaseName" @change="filterCases">
          <option value="">Всі</option>
          <option v-for="caseName in uniqueCaseNames" :key="caseName" :value="caseName">{{ caseName }}</option>
        </select>
      </div>
    </div>
    <div>
      <label>Фільтрувати по назві акаунту:</label>
      <div>
        <select v-model="filterAccountName" @change="filterCases">
          <option value="">Всі</option>
          <option v-for="accountName in uniqueAccountNames" :key="accountName" :value="accountName">{{ accountName }}</option>
        </select>
      </div>
    </div>
    <table v-if="filteredCases.length">
      <thead>
        <tr>
          <th>ID</th>
          <th>Назва кейсу</th>
          <th @click="sortCases('price')">Ціна</th>
          <th>Дата додавання</th>
          <th>Акаунт</th>
          <th>Фото</th>
          <th>Видалити</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="caseItem in sortedCases" :key="caseItem.id">
          <td>{{ caseItem.id }}</td>
          <td>{{ caseItem.case_name }}</td>
          <td>{{ caseItem.price }}</td>
          <td>{{ caseItem.date_added }}</td>
          <td>{{ caseItem.account_name }}</td>
          <td>
            <img v-if="caseItem.photo" :src="'http://localhost/' + caseItem.photo" alt="Case Photo">
          </td>
          <td>
            <button @click="deleteCase(caseItem.id)">Видалити</button>
          </td>
        </tr>
        <tr>
          <td colspan="7"><strong>Загальна сума: {{ totalPrice.toFixed(2) }}</strong></td>
        </tr>
      </tbody>
    </table>
    <p v-else>No data available</p>
    <p v-if="error">{{ error }}</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      cases: [],
      error: null,
      sortKey: '',
      sortAsc: true,
      filterMonth: '',
      filterCaseName: '',
      filterAccountName: '',
      filteredCases: [] // Initialize as an empty array
    };
  },
  computed: {
    sortedCases() {
      return this.filteredCases.slice().sort((a, b) => {
        if (this.sortKey) {
          const modifier = this.sortAsc ? 1 : -1;
          if (a[this.sortKey] < b[this.sortKey]) return -1 * modifier;
          if (a[this.sortKey] > b[this.sortKey]) return 1 * modifier;
          return 0;
        }
        return 0;
      });
    },
    uniqueCaseNames() {
      return [...new Set(this.cases.map(c => c.case_name))];
    },
    uniqueAccountNames() {
      return [...new Set(this.cases.map(c => c.account_name))];
    },
    totalPrice() {
      const total = this.filteredCases.reduce((sum, caseItem) => sum + parseFloat(caseItem.price), 0);
      console.log("Total Price Computation: ", total); // Debugging log
      return total;
    }
  },
  created() {
    this.fetchCases();
  },
  methods: {
    fetchCases() {
      fetch('http://localhost/case-cs2/backend/server.php')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            this.cases = data.cases;
            this.filteredCases = this.cases; // Initialize filteredCases after fetching data
            console.log("Fetched Cases: ", this.cases); // Debugging log
          } else {
            this.error = data.error;
          }
        })
        .catch(error => {
          this.error = `Failed to fetch data: ${error.message}`;
        });
    },
    deleteCase(id) {
      fetch('http://localhost/case-cs2/backend/server.php', {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          this.fetchCases();
        } else {
          alert(data.error);
        }
      })
      .catch(error => {
        this.error = `Failed to delete case: ${error.message}`;
      });
    },
    sortCases(key, ascending = true) {
      this.sortKey = key;
      this.sortAsc = ascending;
    },
    filterCases() {
      this.filteredCases = this.cases.filter(caseItem => {
        const matchesMonth = this.filterMonth ? caseItem.date_added.startsWith(this.filterMonth) : true;
        const matchesCaseName = this.filterCaseName ? caseItem.case_name === this.filterCaseName : true;
        const matchesAccountName = this.filterAccountName ? caseItem.account_name === this.filterAccountName : true;
        return matchesMonth && matchesCaseName && matchesAccountName;
      });
      console.log("Filtered Cases: ", this.filteredCases); // Debugging log
    }
  }
};
</script>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 10px;
  border: 1px solid #ccc;
  text-align: left;
}

th {
  cursor: pointer;
}

img {
  max-width: 100px;
}

button {
  font-family: inherit; /* Ensure button uses the same font as the text */
  margin: 5px;
}

div {
  margin-bottom: 10px;
}

label,
input,
select {
  display: block;
  width: 100%;
  max-width: 300px;
}

input,
select {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-top: 5px;
  box-sizing: border-box;
}
</style>
