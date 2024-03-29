<script setup>
import { onMounted, ref } from 'vue';
import {useRoute} from 'vue-router';
import {fetchAllCards, filterBySetCode} from '../services/cardService';

const cards = ref([]);
const loadingCards = ref(true);
let setCode = ref('');
const $route = useRoute();

async function loadCards() {
    loadingCards.value = true;
    cards.value = await fetchAllCards();
    loadingCards.value = false;
}

async function loadSetCodeCards() {
  loadingCards.value = true;
  await filterBySetCode($route.path, `setCode=${setCode.value}`).then((data) => {
    cards.value = data;
    loadingCards.value = false;
  });
}

onMounted(() => {
    loadCards();
});

</script>

<template>
    <div>
        <h1>Toutes les cartes</h1>
    </div>
    <div class="card-list">
        <div v-if="loadingCards">Loading...</div>
        <div v-else>
          <div>
            <p>Filtre</p>
            <input type="text" v-model="setCode" placeholder="Filtrer par code de set..." />
            <button @click="loadSetCodeCards">Filtrer</button>
          </div>
            <div class="card-result" v-for="card in cards" :key="card.id">
                <router-link :to="{ name: 'get-card', params: { uuid: card.uuid } }">
                    {{ card.name }} <span>({{ card.uuid }})</span>
                </router-link>
            </div>
        </div>
    </div>
</template>
