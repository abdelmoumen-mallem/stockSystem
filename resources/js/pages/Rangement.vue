<template>
    <div class="rack-config">
      <!-- Section: Création d'une allée -->
      <div class="section">
        <div class="input-container">
          Nom de l'allée: <input type="text" v-model="alleyName">
          Nombre d'alveole: <input type="number" v-model="numAlveoles">
          <div class="inline-block">
            <v-btn @click="addAlley" class="px-4 py-2 bg-green font-semibold rounded">Ajouter l'allée</v-btn>
         </div>
        </div>
      </div>
  
      <!-- Section: Paramètres des alvéoles -->
      <div v-for="(alley, index) in alleys" :key="index">
        <h3>Allée {{ alley.name }}</h3>
        <v-btn class="px-4 py-2 bg-blue font-semibold rounded" @click="duplicateAlveole(index)">Dupliquer l'alvéole 1</v-btn>
        
        <div class="rack">

          <div v-for="(alveole, alveoleIndex) in alley.alveoles" :key="alveoleIndex" class="alveole">
            <h4>{{ alley.name }} {{ alveoleIndex + 1 }}</h4>
            <div class="input-container">
                <input type="number" class="rackPlace" v-model="alveole.numCases" title="Nombre d'emplacement">
            </div>
            <div class="input-container">
                <input type="number" class="rackPlace" v-model="alveole.numRows" title="Nombre d'etages">
            </div>
            <div class="rack-row" v-for="rowIndex in alveole.numRows" :key="rowIndex">
              <div class="case" v-for="caseIndex in alveole.numCases" :key="caseIndex"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  
  export default {
    name: 'RackConfig',
    setup() {
      // Données des allées
      const alleys = ref([]);
  
      // Nom de l'allée et nombre d'alvéoles
      const alleyName = ref('');
      const numAlveoles = ref(1);

      const duplicateAlveole = (alleyIndex) => {
        const alley = alleys.value[alleyIndex];
        if (alley && alley.alveoles && alley.alveoles.length > 1) {
            const firstAlveole = alley.alveoles[0]; // Récupérer les valeurs de l'alvéole 1
            for (let i = 1; i < alley.alveoles.length; i++) {
            alley.alveoles[i].numCases = firstAlveole.numCases;
            alley.alveoles[i].numRows = firstAlveole.numRows;
            }
        }
     };


  
      // Fonction pour ajouter une allée avec ses alvéoles
      const addAlley = () => {
        const alveoles = [];
        for (let i = 0; i < numAlveoles.value; i++) {
          alveoles.push({ numCases: 1, numRows: 1 });
        }
        alleys.value.push({
          name: alleyName.value,
          alveoles
        });
        // Réinitialisation des valeurs après l'ajout de l'allée
        alleyName.value = '';
        numAlveoles.value = 1;
      };
  
      return {
        alleys,
        alleyName,
        numAlveoles,
        addAlley,
        duplicateAlveole
      };
    }
  };
  </script>
  
  <style scoped>
  .rack-config {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 20px;
  }
  
  .section {
    background-color: #f9f9f9;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .section h2 {
    margin-top: 0;
  }
  
  .input-container {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    flex-direction: column;
  }
  
  .input-container input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .rackPlace{
    max-width: 70px;
  }
  
  .button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .button:hover {
    background-color: #0056b3;
  }
  
  .rack {
    display: flex;
    gap: 10px;
    overflow-x: auto;
  }
  
  .alveole {
    border: 1px solid #ccc;
    padding: 10px;
  }
  
  .rack-row {
    display: flex;
    gap: 5px;
  }
  
  .case {
    width: 30px;
    height: 30px;
    margin-bottom: 2px;
    background-color: #007bff ;
  }
  </style>
  