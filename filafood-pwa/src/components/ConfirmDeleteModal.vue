<script setup>
import { ref } from "vue";

const props = defineProps({
  itemId: {
    type: String,
    required: false,
    default: null,
  },
});
const emit = defineEmits(["confirm-delete"]);

const isModalOpen = ref(false);

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const confirmDelete = () => {
  if (props.itemId !== null) {
    emit("confirm-delete", props.itemId);
    closeModal();
  }
};

defineExpose({ openModal });
</script>

<template>
  <v-dialog v-model="isModalOpen" persistent max-width="400">
    <v-card>
      <v-card-title class="text-h6">Confirmar Exclusão</v-card-title>
      <v-card-text>
        Deseja excluir o registro? Esta ação não pode ser desfeita.
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="secondary" text @click="closeModal"> Cancelar </v-btn>
        <v-btn color="red" @click="confirmDelete"> Confirmar </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
