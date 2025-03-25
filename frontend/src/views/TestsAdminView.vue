<template>
  <h1>Admin - Tesztek kezelése</h1>
  <!-- <div class="container"> -->

    <!-- Teszt létrehozása gomb -->
    <!-- <button class="btn btn-primary" @click="openModal">Új teszt létrehozása</button> -->

    <!-- Teszt létrehozása Modal -->
    <!-- <div v-if="showModal" class="modal fade show d-block" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Új Teszt</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <label>Teszt neve:</label>
            <input v-model="testName" class="form-control mb-3" type="text" /> -->

            <!-- Témakörök kiválasztása -->
            <!-- <label>Témakörök:</label>
            <div class="form-check">
              <input type="checkbox" id="allTopics" class="form-check-input" v-model="selectAllTopics" @change="toggleAllTopics">
              <label for="allTopics" class="form-check-label">Mindegyik</label>
            </div>
            <div v-for="topic in topics" :key="topic.id" class="form-check">
              <input type="checkbox" class="form-check-input" v-model="selectedTopics" :value="topic.id" />
              <label class="form-check-label">{{ topic.category }}</label>
            </div> -->

            <!-- Kérdéstípusok kiválasztása -->
            <!-- <label class="mt-3">Kérdéstípusok:</label>
            <div class="form-check">
              <input type="checkbox" id="allTypes" class="form-check-input" v-model="selectAllTypes" @change="toggleAllTypes">
              <label for="allTypes" class="form-check-label">Mindegyik</label>
            </div>
            <div v-for="type in questionTypes" :key="type.id" class="form-check">
              <input type="checkbox" class="form-check-input" v-model="selectedTypes" :value="type.id" />
              <label class="form-check-label">{{ type.questionCategory }}</label>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="closeModal">Mégse</button>
            <button class="btn btn-success" @click="generateTest">Készítés</button>
          </div>
        </div>
      </div>
    </div> -->

    <!-- Teszt kérdéseinek megjelenítése -->
    <!-- <div v-if="testGenerated">
      <h2>{{ testName }}</h2>
      <div v-for="(question, index) in testQuestions" :key="question.id" class="card my-3">
        <div class="card-body">
          <h5 class="card-title">{{ index + 1 }}. {{ question.question }}</h5>
          <div v-for="answer in question.answers" :key="answer.id" class="form-check">
            <input type="radio" :id="'answer' + answer.id" :name="'question' + question.id" class="form-check-input"
                   v-model="userAnswers[question.id]" :value="answer.id" />
            <label class="form-check-label" :for="'answer' + answer.id">{{ answer.answer }}</label>
          </div>
        </div>
      </div>
      <button class="btn btn-primary" @click="submitTest">Készen vagyok</button>
    </div> -->

    <!-- Eredmények kiértékelése -->
    <!-- <div v-if="testSubmitted">
      <h2>{{ testName }} - Eredmény</h2>
      <p>Pontszám: {{ score }} / 10 ({{ scorePercentage }}%)</p>
      <div v-for="(question, index) in testQuestions" :key="question.id" class="card my-3">
        <div class="card-body">
          <h5 class="card-title">{{ index + 1 }}. {{ question.question }}</h5>
          <div v-for="answer in question.answers" :key="answer.id"
               :class="{'text-success fw-bold': answer.rightAnswer, 'text-danger': userAnswers[question.id] === answer.id && !answer.rightAnswer}">
            <span v-if="answer.rightAnswer">✔</span>
            <span v-else-if="userAnswers[question.id] === answer.id">✖</span>
            {{ answer.answer }}
          </div>
        </div>
      </div>
      <button class="btn btn-secondary" @click="exitTest">Kilépés</button>
    </div>
  </div> -->
</template>

<script>
// import axios from "axios";
// import { BASE_URL } from "@/helpers/baseUrls";
// import { useAuthStore } from "@/stores/useAuthStore";

// export default {
//   data() {
//     return {
//       store: useAuthStore(),
//       showModal: false,
//       testName: "",
//       topics: [],
//       questionTypes: [],
//       selectedTopics: [],
//       selectedTypes: [],
//       selectAllTopics: false,
//       selectAllTypes: false,
//       testGenerated: false,
//       testQuestions: [],
//       userAnswers: {},
//       testSubmitted: false,
//       score: 0
//     };
//   },
//   computed: {
//     scorePercentage() {
//       return Math.round((this.score / 10) * 100);
//     }
//   },
//   async created() {
//     await this.fetchTopics();
//     await this.fetchQuestionTypes();
//   },
//   methods: {
//     async fetchTopics() {
//       const response = await axios.get(`${BASE_URL}/categories`, { headers: { Authorization: `Bearer ${this.store.token}` } });
//       this.topics = response.data.data;
//     },
//     async fetchQuestionTypes() {
//       const response = await axios.get(`${BASE_URL}/questionTypes`, { headers: { Authorization: `Bearer ${this.store.token}` } });
//       this.questionTypes = response.data.data;
//     },
//     toggleAllTopics() {
//       this.selectedTopics = this.selectAllTopics ? this.topics.map(t => t.id) : [];
//     },
//     toggleAllTypes() {
//       this.selectedTypes = this.selectAllTypes ? this.questionTypes.map(t => t.id) : [];
//     },
//     openModal() {
//       this.showModal = true;
//     },
//     closeModal() {
//       this.showModal = false;
//     },
//     async generateTest() {
//   try {
//     const response = await axios.get(`${BASE_URL}/questions`, {
//       params: {
//         topics: this.selectedTopics.length > 0 ? this.selectedTopics : null,
//         types: this.selectedTypes.length > 0 ? this.selectedTypes : null,
//         limit: 10
//       },
//       headers: { Authorization: `Bearer ${this.store.token}` }
//     });

//     this.testQuestions = response.data.questions.map(q => ({
//       ...q,
//       answers: []
//     }));

//     // Válaszok lekérése az adott kérdésekhez
//     for (let question of this.testQuestions) {
//       const answerResponse = await axios.get(`${BASE_URL}/answers`, {
//         params: { questionId: question.id },
//         headers: { Authorization: `Bearer ${this.store.token}` }
//       });
//       question.answers = answerResponse.data.answers;
//     }

//     this.testGenerated = true;
//     this.showModal = false;
//   } catch (error) {
//     console.error("Hiba a teszt generálásakor:", error);
//   }
// },
//     submitTest() {
//       this.testSubmitted = true;
//       this.score = this.testQuestions.filter(q => q.answers.some(a => a.rightAnswer && a.id === this.userAnswers[q.id])).length;
//     },
//     exitTest() {
//       this.testGenerated = false;
//       this.testSubmitted = false;
//     }
//   }
// };
</script>
