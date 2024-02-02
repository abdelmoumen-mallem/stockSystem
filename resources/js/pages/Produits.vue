<template>
    <v-select
      v-model="selectedFournisseur"
      :items="fournisseurs.map(fournisseur => fournisseur.nom_entreprise)"
      :item-value="fournisseurs.map(fournisseur => fournisseur.id)"
      label="Sélectionnez un fournisseur"
    ></v-select>

    <div v-if="fournisseurId">
        <v-container>
            <v-btn
                @click="
                    cancel();
                    errorMessages = ''
                    showAddDialog = true;
                "
                color="primary"
                >Ajouter un produit</v-btn
            >

            <!-- Tableau des produits -->
            <v-data-table
                :headers="headers"
                :items="produits"
                items-per-page-text="Item par page"
                item-key="id"
                :search="search"
                class="elevation-1"
            >
                <template v-slot:top>
                    <v-text-field
                        v-model="search"
                        label="Rechercher"
                        single-line
                        hide-details
                    ></v-text-field>
                </template>
                <template v-slot:item.nom_produit="props">
                    <v-btn text @click="editProduit(props.item.id)">
                      {{ props.item.nom_produit  }}
                   </v-btn>
                </template>
                <template v-slot:item.actions="props">
                    <td>
                        <v-btn
                            icon
                            @click="confirmDelete(props.item.id)"
                            color="red"
                        >
                            <v-icon>mdi-delete</v-icon>
                        </v-btn>
                    </td>
                </template>
            </v-data-table>

            <!-- Dialogue d'ajout/modification -->
            <v-dialog
                v-model="showAddDialog"
                max-width="500"
                @hide="resetDialog"
                persistent
            >
                <v-card>
                    <v-card-title>{{
                        editingProduit
                            ? "Modifier un produit"
                            : "Ajouter un produit"
                    }}</v-card-title>
                    <v-card-text>
                        <div id="advertissement" class="error-message text-red fixed top-0 left-0 w-full bg-white p-4 z-10" v-if="errorMessages">
                            {{ errorMessages }}
                        </div>
                        <v-form ref="form">
                            <v-text-field
                                v-model="editedProduit.nom_produit"
                                label="Nom"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedProduit.reference"
                                label="Réference"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedProduit.description"
                                label="Description"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedProduit.prix_achat"
                                type="number"
                                label="Prix d'achat"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedProduit.prix_vente"
                                type="number"
                                label="Prix de vente"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedProduit.quantite_min_stock"
                                type="number"
                                label="Qte stock min"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedProduit.quantite_stock"
                                type="number"
                                label="Qte total"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedProduit.code_barres"
                                label="Code barre"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedProduit.taxes"
                                type="number"
                                label="TVA"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedProduit.remises"
                                type="number"
                                label="Remise"
                            ></v-text-field>
                            <v-switch v-if="editingProduit" v-model="switchProduitStatut" color="blue" label-color="blue"></v-switch>
                            <v-switch v-if="editingProduit" v-model="switchProduitEcommerce" color="blue" label-color="blue"></v-switch>
                            <v-text-field
                                v-model="editedProduit.notes"
                                label="Note"
                            ></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions class="custom-card-actions">
                        <v-btn @click="saveProduit" color="primary">{{
                            editingProduit ? "Modifier" : "Ajouter"
                        }}</v-btn>
                        <v-btn @click="cancel" color="error">Annuler</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!-- Utilisation du ConfirmationDialog pour la suppression -->
            <confirmation-dialog
                :show-dialog="showDeleteConfirm"
                @confirm="deleteProduit(deleteItemId)"
                @cancel="showDeleteConfirm = false"
                :message="confirmationMessage"
            >
            </confirmation-dialog>

            <Loader :loading="isLoading" />

            <Toast :show="showToast"/>

        </v-container>
    </div>
</template>
  
<script setup>

import { ref, onMounted, watch } from "vue";
import UseApi from "../composables/UseApi";
import ConfirmationDialog from "../components/ConfirmationDialog.vue";
import Loader from "../components/Loader.vue";
import Toast from "../components/Toast.vue";
  
  // Gestion crud
const { $get, $post, $put, $delete } = UseApi();

// colonnes du tableau
const headers = [
    { text: "Nom", value: "nom_produit" },
    { text: "Actions", value: "actions", sortable: false },
];
  
const fournisseurs = ref([]);
const selectedFournisseur = ref(null); 
const fournisseurId = ref(null);

const produits = ref([]);
const search = ref("");
const showAddDialog = ref(false);
const editingProduit = ref(null);
const errorMessages = ref("");
const switchProduitStatut = ref()
const switchProduitEcommerce = ref()
const editedProduit = ref({
    nom_produit: "",
    reference: "",
    description: "",
    prix_achat: "",
    prix_vente: "",
    quantite_min_stock: "",
    quantite_stock: "",
    code_barres: "",
    taxes: "",
    remises: "",
    status: "",
    e_commerce: "",
    notes: "",
    fournisseur_id: "",
});
const isLoading = ref(false);
const showToast = ref(false);

// Variables pour la suppression
const showDeleteConfirm = ref(false);
const deleteItemId = ref(null);
// Modification ici pour initialiser confirmationMessage comme une référence
const confirmationMessage = ref("");
  
// Récupération des fournisseurs
const fetchData = async () => {
const response = await $get("fournisseurs");
fournisseurs.value = response.fournisseurs;
};

// Récupération des fournisseurs
const fetchDataProduit = async () => {
const response = await $get(`produits/${editedProduit.fournisseur_id}`);
produits.value = response.produits;
};

// Edition des fournisseurs
const editProduit = (id) => {
    editingProduit.value = id;
    const produit = produits.value.find((f) => f.id === id);
    editedProduit.value = { ...produit };
    showAddDialog.value = true;
    errorMessages.value = ''
    switchProduitStatut.value = produit.status == 1 ? true : false;
    switchProduitEcommerce.value = produit.e_commerce == 1 ? true : false;
};

const saveProduit = async () => {
    try {
        isLoading.value = true;

        

        if (editingProduit.value) {

            editedProduit.value.status = switchProduitStatut.value == true ? 1 : 0
            editedProduit.value.e_commerce = switchProduitEcommerce.value == true ? 1 : 0

            // Effectuer une mise à jour
            await $put(
                `produits/${editingProduit.value}`,
                editedProduit.value
            );
        } else {
            // Effectuer une création
            editedProduit.value.fournisseur_id = fournisseurId.value.id
           
            await $post("produits", editedProduit.value);
        }

        // Actualiser la liste des fournisseurs
        fetchDataProduit();
        // Remet les info a zero
        cancel();

        showToast.value = true
        setTimeout(() => {
          showToast.value = false;
        }, 3000);

        errorMessages.value = "";
    }  catch (error) {
    // Gérer l'erreur ici
    if (error.response && error.response.data && error.response.data.message) {
      errorMessages.value = error.response.data.message;
    } else {
      errorMessages.value = "Une erreur s'est produite lors de la sauvegarde du fournisseur.";
    }
  }finally {
    isLoading.value = false; 
  }
};

const cancel = () => {
    editedProduit.value = {
        nom_produit: "",
        reference: "",
        description: "",
        prix_achat: "",
        prix_vente: "",
        quantite_min_stock: "",
        quantite_stock: "",
        code_barres: "",
        taxes: "",
        remises: "",
        status: "",
        e_commerce: "",
        notes: "",
        fournisseur_id: "",
    };
    editingProduit.value = null;
    fetchDataProduit();
    showAddDialog.value = false;

};

// Méthode pour confirmer la suppression
const confirmDelete = (id) => {
    const produit = produits.value.find((f) => f.id === id);
    confirmationMessage.value = `Êtes-vous sûr de vouloir supprimer le produit ${
        produit ? produit.nom_produit : ""
    } ?`;
    showDeleteConfirm.value = true;
    deleteItemId.value = id;
};

// Méthode pour effectuer la suppression après confirmation
const deleteProduit = async (id) => {
  try {
    isLoading.value = true;

    await $delete(`produits/${id}`);

    // Actualiser la liste des fournisseurs
    fetchDataProduit();

    // Réinitialiser les valeurs
    deleteItemId.value = null;
    showDeleteConfirm.value = false;
  }catch (error) {
    errorMessages.value = "Une erreur s'est produite lors de la suppression du fournisseur.";
  }finally {
    isLoading.value = false; 
  }
};

const resetDialog = () => {
    editingProduit.value = null;
};

  onMounted(() => {
    fetchData();
  });

  // Récupere id fournisseur
  watch(selectedFournisseur, (selectedFournisseurName) => {
    fournisseurId.value = fournisseurs.value.find(f => f.nom_entreprise === selectedFournisseurName);
    editedProduit.fournisseur_id = fournisseurId.value.id;
    fetchDataProduit()
  });

  
  </script>

<style>
.custom-card-actions {
  position: fixed;
  bottom: -10px;
  width: 100%;
  z-index: 10;
}
</style>
  