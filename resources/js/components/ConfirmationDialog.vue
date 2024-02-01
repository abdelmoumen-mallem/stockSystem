<template>
    <div v-if="showDialog" class="confirmation-dialog">
      <div class="confirmation-content">
        <p>{{ message }}</p>
        <div>
          <button @click="confirmAction" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2 mr-2">
            Confirmer
          </button>
          <button @click="cancelAction" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">
            Annuler
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, defineProps, defineEmits } from 'vue';
  
  const { showDialog, message } = defineProps(['showDialog', 'message']);
  const emit = defineEmits(['confirm', 'cancel']);
  
  const confirmAction = () => {
    emit('confirm'); 
  };
  
  const cancelAction = () => {
    emit('cancel'); 
  };
  </script>
  
  <style scoped>
  .confirmation-dialog {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
  }
  
  .confirmation-content {
    background: white;
    padding: 20px;
    border-radius: 4px;
    text-align: center;
  }
  
  .confirmation-content p {
    margin-bottom: 20px;
  }
  </style>
  