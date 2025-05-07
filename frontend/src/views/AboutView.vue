<template>
  <div class="my-container">
    <div class="about-container">
      <h1 class="title">Rólunk</h1>

      <section class="about-section">
        <p>
          Üdvözlünk! Mi egy szenvedélyes csapat vagyunk, akik azért dolgoznak,
          hogy a lehető legjobb élményt nyújtsák felhasználóiknak.
        </p>
      </section>

      <section class="mission">
        <h2>Küldetésünk</h2>
        <p>
          Egy olyan felületet biztosítunk, ahol fel lehet készülni a történelem
          érettségire közép, de akár emelt szinten is.
        </p>
      </section>

      <section class="team">
        <h2>Csapatunk</h2>
        <div class="team-grid">
          <div
            class="team-member"
            v-for="member in teamMembers"
            :key="member.id"
          >
            <img
              :src="`/images/${member.image}`"
              :alt="member.name"
              class="team-img"
            />
            <h3>{{ member.name }}</h3>
            <p>{{ member.role }}</p>
          </div>
        </div>
      </section>

      <section class="contact">
        <h2>Kapcsolat</h2>
        <p>
          Elérsz minket emailben:
          <a href="mailto:info@weboldal.hu">info@weboldal.hu</a>
        </p>
      </section>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      teamMembers: [
        {
          id: 1,
          name: "Kovács János",
          role: "Csoportvezető",
          image: "profil2.jpg",
        },
        {
          id: 2,
          name: "Jáger Kristóf",
          role: "Fejlesztő",
          image: "profil1.jpg",
        },
      ],
    };
  },
};
</script>

<style scoped>
.my-container {
  background-image: url("/images/parchment-texture.jpg");
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
}

.about-container {
  max-width: 800px;
  margin: auto;
  padding: 20px;
  text-align: center;
}

.title {
  font-size: 2.5rem;
  margin-bottom: 20px;
  color: #5a3e1b;
}

.about-section,
.mission,
.team,
.contact {
  color: #5a3e1b;
  margin-bottom: 30px;
  background: rgba(255, 248, 220, 0.9);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  border: 2px solid #8b5a2b;
}

.team-grid {
  display: flex;
  justify-content: center;
  gap: 20px;
  flex-wrap: wrap;
}

.team-member {
  text-align: center;
  max-width: 200px;
}

.team-img {
  width: 100%;
  border-radius: 50%;
}

a {
  color: #007bff;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
</style>
