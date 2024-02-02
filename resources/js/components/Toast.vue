<template>
    <div v-if="show" class="toast">
      Enregistrement effectué
    </div>
  </template>
  
  <script>
  export default {
    props: {
      show: Boolean,
    },
    
  };
  </script>
  
  <style scoped>
  .toast {
    position: fixed;
    bottom: 20px;
    right: 5px;
    transform: translateX(-50%);
    background-color: green;
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
    opacity: 0.9;
    z-index: 9999;
  }
  </style>
  