<template>
    <v-form ref="form">
  
      <v-select
        v-model="select"
        :items="items"
        :rules="[v => !!v || 'Fournisseur est requis']"
        label="Fournisseur"
        no-data-text="Aucune donnée disponible"
        required
      ></v-select>

      <v-text-field
        v-model="editedEntree.numero_facture"
        :rules="[v => !!v || 'Numéro facture est requis']"
        label="Numéro de facture"
        required
      ></v-text-field>

      <v-text-field
        v-model="editedEntree.transporteur"
        :rules="[v => !!v || 'Transporteur est requis']"
        label="Nom du transporteur"
        required
      ></v-text-field>

      <v-text-field
        v-model="editedEntree.plaque_immat"
        :rules="[v => !!v || 'Immat est requis']"
        label="Plaque immat"
        required
      ></v-text-field>

      <v-text-field
        v-model="editedEntree.commentaire"
        label="Commentaire"
      ></v-text-field>

      <div id="advertissement" class="error-message text-red fixed top-0 left-0 w-full bg-white p-4 z-10" v-if="errorMessages">
        {{ errorMessages }}
     </div>

      <div class="text-right">
        <v-btn
          color="success"
          class="mt-4"
          @click="validate"
        >
          ENVOYER
        </v-btn>
  
      </div>
    </v-form>

    <Loader :loading="isLoading" />

  </template>
  
  <script setup>
import { ref, onMounted } from "vue";
import UseApi from "../composables/UseApi";
import Loader from "../components/Loader.vue";
import { useRouter } from "vue-router";

const router = useRouter();

// Gestion crud
const { $get, $post, $put, $delete } = UseApi();

const isLoading = ref(false);
const form = ref(null); 
const fournisseurs = ref([]);
const items = ref([]);
const select = ref(null);
const errorMessages = ref("");
const editedEntree = ref({
    numero_facture: "",
    transporteur: "",
    plaque_immat: "",
    commentaire: "",
    fournisseur_id: "",
});

// Récuperation des fournisseurs
const fetchData = async () => {
    const response = await $get("fournisseurs");
    fournisseurs.value = response.fournisseurs;
    items.value = fournisseurs.value.map(fournisseur => fournisseur.nom_entreprise);
};
  
const validate = async () => {
    const { valid } = await form.value.validate();
    if (valid) {
        try{
            isLoading.value = true;

            // Récupére l'ID du fournisseur en fonction du nom sélectionné
            const selectedFournisseur = fournisseurs.value.find(f => f.nom_entreprise === select.value);
            editedEntree.value.fournisseur_id = selectedFournisseur.id;
         
            const response = await $post("entrees", editedEntree.value);
            
            if (response && response.message === "OK") {
                //router.push({ name: 'EntreesProduits' });
                router.push({ name: 'EntreesProduits', params: { id: editedEntree.value.numero_facture } });
            }


        }catch (error) {
            if (error.response && error.response.data && error.response.data.message) {
            errorMessages.value = error.response.data.message;
            } else {
            errorMessages.value = "Une erreur s'est produite lors de la sauvegarde du fournisseur.";
            }
        }finally{
            isLoading.value = false; 
        }
    }
    
};

onMounted(() => {
    fetchData();
});
  
  </script>
  