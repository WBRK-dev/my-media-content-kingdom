<script>
export default {
  data() {
    return {
      count: 1,
      incrementValue: 1,
    }
  },

  methods: {
    increment() {
      this.count += Number(this.incrementValue);
    }
  }
}
</script>

<template>
    <button @click="increment()">Count is: {{ count }}</button>
    <input type="number" v-model="incrementValue" value="{{ incrementValue }}">
</template>