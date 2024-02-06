<template>
    <div>
        <v-container>
            <v-btn
                @click="
                    cancel();
                    errorMessages = ''
                    showAddDialog = true;
                "
                color="primary"
                >Ajouter un fournisseur</v-btn
            >

            <!-- Tableau des fournisseurs -->
            <v-data-table
                :headers="headers"
                :items="fournisseurs"
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
                <template v-slot:item.nom_entreprise="props">
                    <v-btn text @click="editFournisseur(props.item.id)">
                      {{ props.item.nom_entreprise  }}
                   </v-btn>
                </template>
                <template v-slot:item.actions="props">
                    <td>
                        <v-btn
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
            >
                <v-card>
                    <v-card-title>{{
                        editingFournisseur
                            ? "Modifier un fournisseur"
                            : "Ajouter un fournisseur"
                    }}</v-card-title>
                    <v-card-text>
                        <div id="advertissement" class="error-message text-red fixed top-0 left-0 w-full bg-white p-4 z-10" v-if="errorMessages">
                            {{ errorMessages }}
                        </div>
                        <v-form ref="form">
                            <v-text-field
                                v-model="editedFournisseur.nom_entreprise"
                                label="Nom"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedFournisseur.adresse"
                                label="Adresse"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedFournisseur.ville"
                                label="Ville"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedFournisseur.code_postal"
                                label="Code postal"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedFournisseur.nom_contact"
                                label="Nom du contact"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedFournisseur.telephone"
                                label="Télephone"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedFournisseur.email"
                                label="Email"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedFournisseur.site_web"
                                label="Site web"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedFournisseur.numero_tva"
                                label="Numéro TVA"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedFournisseur.notes"
                                label="Note"
                            ></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn @click="saveFournisseur" color="primary">{{
                            editingFournisseur ? "Modifier" : "Ajouter"
                        }}</v-btn>
                        <v-btn @click="cancel" color="error">Annuler</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!-- Utilisation du ConfirmationDialog pour la suppression -->
            <confirmation-dialog
                :show-dialog="showDeleteConfirm"
                @confirm="deleteFournisseur(deleteItemId)"
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
import { ref, onMounted } from "vue";
import UseApi from "../composables/UseApi";
import ConfirmationDialog from "../components/ConfirmationDialog.vue";
import Loader from "../components/Loader.vue";
import Toast from "../components/Toast.vue";

// Gestion crud
const { $get, $post, $put, $delete } = UseApi();

// colonnes du tableau
const headers = [
    { text: "Nom", value: "nom_entreprise" },
    { text: "Actions", value: "actions", sortable: false },
];

const fournisseurs = ref([]);
const search = ref("");
const showAddDialog = ref(false);
const editingFournisseur = ref(null);
const errorMessages = ref("");
const editedFournisseur = ref({
    nom_entreprise: "",
    adresse: "",
    ville: "",
    code_postal: "",
    nom_contact: "",
    telephone: "",
    email: "",
    site_web: "",
    numero_tva: "",
    notes: "",
});
const isLoading = ref(false);
const showToast = ref(false);

// Variables pour la suppression
const showDeleteConfirm = ref(false);
const deleteItemId = ref(null);
// Modification ici pour initialiser confirmationMessage comme une référence
const confirmationMessage = ref("");

// Récuperation des fournisseurs
const fetchData = async () => {
    const response = await $get("fournisseurs");
    fournisseurs.value = response.fournisseurs;
};

// Edition des fournisseurs
const editFournisseur = (id) => {
    editingFournisseur.value = id;
    const fournisseur = fournisseurs.value.find((f) => f.id === id);
    editedFournisseur.value = { ...fournisseur };
    showAddDialog.value = true;
    errorMessages.value = ''

};

const saveFournisseur = async () => {
    try {
        isLoading.value = true;

        if (editingFournisseur.value) {
            // Effectuer une mise à jour
            await $put(
                `fournisseurs/${editingFournisseur.value}`,
                editedFournisseur.value
            );
        } else {
            // Effectuer une création
            await $post("fournisseurs", editedFournisseur.value);
        }

        // Actualiser la liste des fournisseurs
        fetchData();
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
    editedFournisseur.value = {
        nom_entreprise: "",
        adresse: "",
        ville: "",
        code_postal: "",
        nom_contact: "",
        telephone: "",
        email: "",
        site_web: "",
        numero_tva: "",
        notes: "",
    };
    editingFournisseur.value = null;
    showAddDialog.value = false;
};

// Méthode pour confirmer la suppression
const confirmDelete = (id) => {
    const fournisseur = fournisseurs.value.find((f) => f.id === id);
    confirmationMessage.value = `Êtes-vous sûr de vouloir supprimer le fournisseur ${
        fournisseur ? fournisseur.nom_entreprise : ""
    } ?`;
    showDeleteConfirm.value = true;
    deleteItemId.value = id;
};

// Méthode pour effectuer la suppression après confirmation
const deleteFournisseur = async (id) => {
  try {
    isLoading.value = true;

    await $delete(`fournisseurs/${id}`);

    // Actualiser la liste des fournisseurs
    fetchData();

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
    editingFournisseur.value = null;
};

onMounted(() => {
    fetchData();
});

</script>
