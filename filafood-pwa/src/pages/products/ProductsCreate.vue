<script setup>
import { reactive, onBeforeMount, onMounted, ref } from "vue";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import useVuelidate from "@vuelidate/core";
import { productRules } from "@/validations/product.rules";
import { VFileUpload } from "vuetify/labs/VFileUpload";
import http from "@/http";

onBeforeMount(() => (document.title = "Cadastrar Produto"));

onMounted(async () => {
  await fetchCategories();
});

const toast = useToast();

const router = useRouter();

const state = reactive({
  data: {
    category_id: "",
    name: "",
    price: "",
    description: "",
    active: true,
  },
  categories: [],
  creating: false,
});

const file = ref(null);

const isDragging = ref(false);

const v$ = useVuelidate(productRules, state);

const handleFileChange = (event) => {
  file.value = event.target.files[0];
};

const handleDragOver = () => {
  isDragging.value = true;
};

const handleDragLeave = () => {
  isDragging.value = false;
};

const handleDrop = (event) => {
  isDragging.value = false;
  const droppedFiles = event.dataTransfer.files;
  if (droppedFiles.length) {
    file.value = droppedFiles[0];
  }
};

const fetchCategories = async () => {
  try {
    const response = await http.get("/categories?active=1");
    state.categories = response.data.data;
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao carregar categorias");
  }
};

const handlerStore = async () => {
  if (!(await v$.value.$validate())) return;
  state.creating = true;
  try {
    const formData = new FormData();
    if (file.value) {
      formData.append("image", file.value);
    }
    formData.append("name", state.data.name);
    formData.append("category_id", state.data.category_id);
    formData.append("price", state.data.price);
    formData.append("description", state.data.description);
    formData.append("active", state.data.active ? 1 : 0);

    const response = await http.post("/products", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    toast.success("Produto cadastrado com sucesso!");
    router.push({ name: "products.index" });
  } catch (error) {
    console.log(error);
    toast.error(error.response?.data?.message || "Erro ao criar produto");
  } finally {
    state.creating = false;
  }
};
</script>

<template>
  <v-responsive>
    <v-row>
      <v-col cols="12">
        <h1 class="mb-3">Cadastrar Produto</h1>
      </v-col>
    </v-row>

    <v-form @submit.prevent="handlerStore">
      <v-row>
        <v-col cols="12" md="12" class="pb-2">
          <v-file-upload
            v-model="file"
            clearable
            density="compact"
            variant="outlined"
            accept="image/*"
            title="Clique ou arraste para selecionar uma imagem"
            @change="handleFileChange"
            @dragover="handleDragOver"
            @dragleave="handleDragLeave"
            @drop="handleDrop"
          >
          </v-file-upload>
        </v-col>

        <v-col cols="12" md="2" class="pb-0">
          <v-text-field
            v-model="state.data.price"
            :error-messages="v$.data.price.$errors.map((e) => e.$message)"
            @input="v$.data.price.$touch"
            @blur="v$.data.price.$touch"
            label="Preço"
            variant="outlined"
            density="compact"
          >
          </v-text-field>
        </v-col>

        <v-col cols="12" md="4" class="pb-0">
          <v-select
            v-model="state.data.category_id"
            label="Categorias"
            :items="state.categories"
            item-title="name"
            item-value="id"
            :error-messages="v$.data.category_id.$errors.map((e) => e.$message)"
            @input="v$.data.category_id.$touch"
            @blur="v$.data.category_id.$touch"
            variant="outlined"
            density="compact"
          >
          </v-select>
        </v-col>

        <v-col cols="12" md="6" class="pb-0">
          <v-text-field
            v-model="state.data.name"
            :error-messages="v$.data.name.$errors.map((e) => e.$message)"
            @input="v$.data.name.$touch"
            @blur="v$.data.name.$touch"
            label="Nome"
            variant="outlined"
            density="compact"
          >
          </v-text-field>
        </v-col>

        <v-col cols="12" md="12" class="pb-0">
          <v-textarea
            v-model="state.data.description"
            :error-messages="v$.data.description.$errors.map((e) => e.$message)"
            @input="v$.data.description.$touch"
            @blur="v$.data.description.$touch"
            label="Descrição"
            variant="outlined"
            density="compact"
            clearable
            rows="3"
          >
          </v-textarea>
        </v-col>

        <v-col cols="12" md="12" class="py-0 ml-2">
          <v-switch
            v-model="state.data.active"
            label="Ativo"
            hide-details
            color="primary"
            density="compact"
            :ripple="false"
            :flat="true"
          >
          </v-switch>
        </v-col>

        <v-col cols="12">
          <v-btn
            :loading="state.creating"
            type="submit"
            color="primary"
            variant="flat"
            class="float-start mr-2"
          >
            Salvar
          </v-btn>
          <v-btn
            v-can="'products.index'"
            :to="{ name: 'products.index' }"
            color="primary"
            variant="tonal"
            class="float-start"
          >
            Cancelar
          </v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-responsive>
</template>
