<template>
  <div class="management-container">
    <div class="form-container">
      <AddCase @case-added="fetchCases" />
      <AddAccount @account-added="fetchAccounts" />
    </div>
    <CaseList ref="caseList" />
  </div>
</template>

<script>
import AddCase from '@/components/AddCase.vue';
import CaseList from '@/components/CaseList.vue';

export default {
  name: 'ManagementPage',
  components: {
    AddCase,
    CaseList
  },
  methods: {
    fetchCases() {
      this.$refs.caseList.fetchCases();
    },
    fetchAccounts() {
      this.$refs.caseList.fetchAccounts();
    }
  }
};
</script>

<style scoped>
.management-container {
  padding: 20px;
}

h1 {
  font-size: 24px;
  margin-bottom: 20px;
}

.form-container {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}

button {
  font-family: inherit; /* Ensure buttons use the same font as the text */
}
</style>
