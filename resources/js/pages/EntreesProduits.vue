<template>
    <div class="grid grid-cols-2 gap-4 text-center">
      <div v-for="(produit, index) in produits" :key="index" @click="handleProductClick(produit)">
        <v-card
            class="mx-auto my-8"
            max-width="200"
            :title="produit.nom_produit"
            :subtitle="'Ref: ' + (produit.reference !== null ? produit.reference : 'Aucune')"
            link>
        </v-card>
      </div>
    </div>

    <v-dialog
        v-model="showAddDialog"
        max-width="500"
        @hide="cancel"
        persistent>
        <v-card>
            <v-card-text>
                <div id="advertissement" class="error-message text-red fixed top-0 left-0 w-full bg-white p-4 z-10" v-if="errorMessages">
                    {{ errorMessages }}
                </div>
                <v-form ref="form">
                    <v-text-field
                        v-model="editedEntree.quantite"
                        :rules="[v => !!v || 'Quantité est requis']"
                        type="number"
                        label="Quantité"
                    ></v-text-field>
                    <v-text-field
                        v-model="editedEntree.prix_unitaire"
                        :rules="[v => !!v || 'Prix d\'achat est requis']"
                        type="number"
                        label="prix d'achat"
                    ></v-text-field>
                    <v-text-field
                        v-model="editedEntree.emplacement_stockage"
                        label="Stockage"
                    ></v-text-field>
                    <v-select
                        v-model="editedEntree.palette"
                        label="Palettes consignés"
                        :items="['Europe', 'Lpr', 'Chep', 'Ipp']"
                    ></v-select>
                    <v-text-field
                        v-model="editedEntree.commentaire"
                        label="Commentaire"
                    ></v-text-field>
                    <hr><br>
                    <fieldset>
                        <legend>Date de peremption</legend>
                        <input type="date" name="dateValue">
                    </fieldset>                
                </v-form>
            </v-card-text>
            <v-card-actions class="acustom-card-actions">
                <v-btn color="primary" @click="validate">Envoyer</v-btn>
                <v-btn color="error" @click="cancel">Annuler</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <Loader :loading="isLoading" />

    <Toast :show="showToast"/>

</template>
  
  <script setup>
import { ref, onMounted } from "vue";
import UseApi from "../composables/UseApi";
import Loader from "../components/Loader.vue";
import { useRouter } from "vue-router";
import { useRoute } from "vue-router";
import Toast from "../components/Toast.vue";

const router = useRouter();
const route = useRoute();

const produits = ref([]);
const showAddDialog = ref(false);
const errorMessages = ref("");
const form = ref(null); 

const isLoading = ref(false);
const showToast = ref(false);

const editedEntree = ref({
    produit_id: "",
    entree_info_id: "",
    quantite: "",
    prix_unitaire: "",
    emplacement_stockage: "",
    date_peremption: "",
    commentaire: "",
    palette: "",
});

// Gestion crud
const { $get, $post, $put, $delete } = UseApi();

// Récuperation des fournisseurs
const fetchData = async () => {
    const response = await $get(`entrees/${route.params.id}`);
    produits.value = response.produits;
};

const handleProductClick = (produit) => {
    showAddDialog.value = true;
    editedEntree.value.prix_unitaire = produit.prix_achat;  
    editedEntree.value.produit_id = produit.id;
};

const cancel = () => {
    editedEntree.value = {
    quantite: "",
    prix_unitaire: "",
    emplacement_stockage: "",
    date_peremption: "",
    };
    fetchData();
    showAddDialog.value = false;

};

const validate = async () => {
    const { valid } = await form.value.validate();
    if (valid) {
        try{
            isLoading.value = true;
            
            editedEntree.value.entree_info_id = route.params.id;
            editedEntree.value.date_peremption = document.querySelector('input[name="dateValue"]').value;
         
            const response = await $post("entreesProduits", editedEntree.value);
            
            if (response && response.message === "OK") {
                showAddDialog.value = false;
                showToast.value = true
                setTimeout(() => {
                showToast.value = false;
                }, 3000);
            }

            errorMessages.value = "";

            cancel();



        }catch (error) {
            console.error("Erreur lors de la sauvegarde :", error);

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
  <style>
  input[type="date"] {
    appearance: none; 
    padding: 0.5rem 1rem; 
    border: 1px solid #ccc;
    border-radius: 4px; 
    background-color: #fff; 
    font-size: 1rem; 
    outline: none; 
  }
  
  </style>