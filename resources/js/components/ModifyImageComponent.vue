<template>
    <v-app>
      <v-main>
        <v-container>
          <v-alert v-model="alert.show" :color="alert.color" dense>
            {{ alert.message }}
          </v-alert>
          <label for="file-upload" class="upload-button">Sélectionner un fichier</label>
          <input id="file-upload" type="file" class="file-input" @change="handleFileChange"  />
          <v-btn :disabled="!imageSrc" @click="openDialog" >Modifier l'image</v-btn>
        </v-container>
      </v-main>
      <v-dialog v-model="dialog" max-width="800" fullscreen>
        <v-card>
          <v-card-title>
            <span class="headline">Modifier l'image</span>
          </v-card-title>
          <v-card-text>
            <div class="cropper-container">
              <img ref="modalImage" :src="modalImageSrc"  />
            </div>
          </v-card-text>
          <v-card-actions>
            <v-btn @click="rotateLeft">Rotation</v-btn>
            <v-btn @click="zoomIn">Zoomer</v-btn>
            <v-btn @click="zoomOut">Dézoomer</v-btn>
            <v-btn @click="downloadCroppedImage">Valider</v-btn>
            <v-btn @click="closeDialog">Fermer</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-app>
  </template>
  
  <script>
  import Cropper from 'cropperjs'
  import 'cropperjs/dist/cropper.css'
  
  export default {
    props: {
      initialimageurl: {
        type: String,
        default: ''
      },
      selectedsupplier: {
        type: String,
        default: '' 
      },
      isaddingproduct: Boolean,
    },
    emits: ['imageuploadedsuccess', 'imagemodified'],
    data() {
      return {
        alert: {
          show: false,
          message: '',
          color: 'success',
        },
        imageSrc: null,
        cropper: null,
        modalImageSrc: null,
        dialog: false,
        zoomLevel: 1,
      }
    },
    beforeUnmount() {
      if (this.cropper) {
        this.cropper.destroy()
      }
    },
    methods: {
      rotateLeft() {
        if (this.cropper) {
          this.cropper.rotate(-90)
        }
      },
  
      handleFileChange(event) {
        const file = event.target.files[0]
        if (file) {
          this.imageSrc = URL.createObjectURL(file)
        } else {
          console.error('test')
        }
      },
      openDialog() {
        if (this.imageSrc) {
          this.modalImageSrc = this.imageSrc
          this.dialog = true
          this.initializeCropper()
        }
      },
      initializeCropper() {
        if (this.cropper) {
          this.cropper.destroy()
        }
  
        this.$nextTick(() => {
          this.cropper = new Cropper(this.$refs.modalImage, {
            aspectRatio: 756 / 1008,
            viewMode: 2,
            dragMode: 'move',
            autoCropArea: 1,
            cropBoxResizable: false,
            cropBoxMovable: false,
          })
        })
      },
  
      showAlert(message, color = 'success') {
        this.alert.message = message
        this.alert.color = color
        this.alert.show = true
        setTimeout(() => {
          this.alert.show = false
        }, 3000)
      },
  
      async downloadCroppedImage() {
        if (this.cropper) {
          const croppedCanvas = this.cropper.getCroppedCanvas({
            width: 756,
            height: 1008,
          })
  
          this.croppedImageSrc = croppedCanvas.toDataURL()
          this.dialog = false
  
          if (this.isaddingproduct === false) {
            this.$emit('imageuploadedsuccess', this.croppedImageSrc) 
          } else {
            this.$emit('imagemodified', this.croppedImageSrc)
          }
        }
      },
  
      closeDialog() {
        this.dialog = false
      },
      zoomIn() {
        this.zoomLevel = Math.min(1, this.zoomLevel + 0.1)
        this.cropper.zoomTo(this.zoomLevel)
      },
      zoomOut() {
        this.zoomLevel = Math.max(0, this.zoomLevel - 0.1)
        this.cropper.zoomTo(this.zoomLevel)
      },
    },
    
  }
  </script>
  
  <style scoped>
  .cropper-container {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f0f0f0;
    max-width: 100%;
    height: auto;
    max-height: 600px;
  }
  
  .file-input {
    display: none;
  }
  
  .upload-button {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .upload-button:hover {
    background-color: #0056b3;
  }
  </style>
  