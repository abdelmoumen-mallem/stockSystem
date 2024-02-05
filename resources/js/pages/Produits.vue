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
                no-data-text="Aucune donnée disponible"
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
                            <div v-if="editingProduit">
                            Utilisation produit<v-switch  v-model="switchProduitStatut" color="blue" label-color="blue"></v-switch>
                            <hr>
                            Activation E-commerce<v-switch  v-model="switchProduitEcommerce" color="blue" label-color="blue"></v-switch>
                            </div>
                            <v-text-field
                                v-model="editedProduit.notes"
                                label="Note"
                            ></v-text-field>

                            <div v-if="!editingProduit">
                               
                                <label class="file-upload-label cursor-pointer bg-blue-500 text-white p-1 rounded flex items-center">
                                    <span class="mr-2">Photo produit n°1</span>
                                    <input type="file" @change="handleFileChange(0)" accept="image/*" class="hidden" />
                                    <i class="mdi mdi-file-upload file-upload-icon" title="Image produit 1"></i>
                                </label>
                                <div class="thumbnail-container">
                                    <img :src="thumbnails[0]" v-if="thumbnails[0]" alt="Thumbnail 1" width="100" height="100" />
                                    <v-btn v-if="thumbnails[0]" @click="deleteThumbnail(0)" color="red" icon >
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                </div>
                                <br>
                              
                                <label class="file-upload-label cursor-pointer bg-blue-500 text-white p-1 rounded flex items-center">
                                    <span class="mr-2">Photo produit n°2</span>                                    <input type="file" @change="handleFileChange(1)" accept="image/*" class="hidden" />
                                    <i class="mdi mdi-file-upload file-upload-icon" title="Image produit 2"></i>
                                </label>
                                <div class="thumbnail-container">
                                    <img :src="thumbnails[1]" v-if="thumbnails[1]" alt="Thumbnail 2" width="100" height="100" />
                                    <v-btn v-if="thumbnails[1]" @click="deleteThumbnail(1)" color="red" icon >
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                </div>
                                <br>

                                <label class="file-upload-label cursor-pointer bg-blue-500 text-white p-1 rounded flex items-center">
                                    <span class="mr-2">Photo produit n°3</span>                                    <input type="file" @change="handleFileChange(2)" accept="image/*" class="hidden" />
                                    <i class="mdi mdi-file-upload file-upload-icon" title="Image produit 3"></i>
                                </label>      
                                <div class="thumbnail-container">
                                    <img :src="thumbnails[2]" v-if="thumbnails[2]" alt="Thumbnail 3" width="100" height="100" />
                                    <v-btn v-if="thumbnails[2]" @click="deleteThumbnail(2)" color="red" icon >
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                </div>
                                                    
                            </div>

                        </v-form>
                    </v-card-text>
                    <v-card-actions class="acustom-card-actions">
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

const thumbnails = ref([null, null, null]);


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

const handleFileChange = (index) => {
      const inputElement = document.querySelectorAll('input[type="file"]')[index];
      if (inputElement && inputElement.files && inputElement.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
          thumbnails.value[index] = e.target.result;
        };
        reader.readAsDataURL(inputElement.files[0]);
      }else {
        thumbnails.value[index] = null;
      }
};

const deleteThumbnail = (index) => {
      // Réinitialise la miniature lorsque l'utilisateur clique sur le bouton "Supprimer"
      thumbnails.value[index] = null;

      // Réinitialise le champ de fichier correspondant
      const inputElement = document.querySelectorAll('input[type="file"]')[index];
      if (inputElement) {
        inputElement.value = '';
      }
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

            // Effectue une mise à jour
            await $put(
                `produits/${editingProduit.value}`,
                editedProduit.value
            );
        } else {
            // Effectue une création
            editedProduit.value.fournisseur_id = fournisseurId.value.id

            // Crée un objet FormData pour envoyer les fichiers et les données
            const formData = new FormData();

            // Ajoute les propriétés de l'objet editedProduit au FormData
            for (const key in editedProduit.value) {
                formData.append(key, editedProduit.value[key]);
            }

            // Ajoute les fichiers d'image et leurs noms au FormData
            const imageFiles = [
                document.querySelectorAll('input[type="file"]')[0].files[0],
                document.querySelectorAll('input[type="file"]')[1].files[0],
                document.querySelectorAll('input[type="file"]')[2].files[0],
            ];

            const currentDate = new Date();
            const year = currentDate.getFullYear().toString().slice(-2);
            const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); 
            const day = currentDate.getDate().toString().padStart(2, '0'); 
            const hour = currentDate.getHours().toString().padStart(2, '0'); 
            const minute = currentDate.getMinutes().toString().padStart(2, '0'); 
            const second = currentDate.getSeconds().toString().padStart(2, '0');

            for (let i = 0; i < imageFiles.length; i++) {
                if (imageFiles[i]) {
                    const uniqueFileName = `${year}${month}${day}${hour}${minute}${second}${i + 1}.jpg`;

                    formData.append(`image_url_${i + 1}_image`, thumbnails.value[i]);
                    formData.append(`image_url_${i + 1}`, uniqueFileName);
                }
            }

            // Effectue une création en envoyant le FormData
            await $post("produits", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
        }

        // Actualise la liste des fournisseurs
        fetchDataProduit();
        // Remet les info a zero
        cancel();

        for (let i = 0; i < 3; i++) {
            deleteThumbnail(i)
        }

        showToast.value = true
        setTimeout(() => {
          showToast.value = false;
        }, 3000);

        errorMessages.value = "";
    }  catch (error) {
    // Gére l'erreur ici
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

    // Actualise la liste des fournisseurs
    fetchDataProduit();

    // Réinitialise les valeurs
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
  bottom: -20px;
  width: 100%;
  z-index: 10;
}

.thumbnail-container {
  display: flex;
  align-items: center;
}

.file-upload-label {
  cursor: pointer;
  display: inline-block;
}

.file-upload-icon {
  cursor: pointer;
  display: inline-block;
}

.file-upload-icon i {
  font-size: 24px;
  color: #007bff; 
}
</style>
  