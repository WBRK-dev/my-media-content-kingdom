<script>
export default {
  // Variables die worden opgeslagen in deze module
  data() {
    return {
      count: 1,
      incrementValue: 1,
    }
  },

  // Functies die kunnen worden gecalled in deze module
  methods: {
    increment() {
      this.count += 1;
    }
  }
}
</script>

<template>
    <!-- On click call je de functie increment van deze module -->
    <!-- Wanneer "count" wordt veranderdt wordt de hele html opnieuw gerendert -->
    <button @click="increment()">Count is: {{ count }}</button>
</template>