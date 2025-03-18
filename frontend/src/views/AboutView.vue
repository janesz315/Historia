<template>
  <div class="about-container">
    <h1 class="title">Rólunk</h1>

    <section class="about-section">
      <p>
        Üdvözlünk! Mi egy szenvedélyes csapat vagyunk, akik azért dolgoznak, hogy a lehető legjobb élményt nyújtsuk felhasználóinknak.
        Célunk, hogy innovatív megoldásokat kínáljunk és egy támogató közösséget építsünk.
      </p>
    </section>

    <section class="mission">
      <h2>Küldetésünk</h2>
      <p>Minőségi szolgáltatást nyújtani, és közösséget építeni minden felhasználónk számára.</p>
    </section>

    <section class="team">
      <h2>Csapatunk</h2>
      <div class="team-grid">
        <div class="team-member" v-for="member in teamMembers" :key="member.id">
          <img :src="`/images/${member.image}`" :alt="member.name" class="team-img" />
          <h3>{{ member.name }}</h3>
          <p>{{ member.role }}</p>
        </div>
      </div>
    </section>

    <section class="contact">
      <h2>Kapcsolat</h2>
      <p>Elérsz minket emailben: <a href="mailto:info@weboldal.hu">info@weboldal.hu</a></p>
    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      teamMembers: [
        { id: 1, name: "Kovács János", role: "Csoportvezető", image: "profil2.jpg" },
        { id: 2, name: "Jáger Kristóf", role: "Fejlesztő", image: "profil1.jpg" },
      ],
    };
  },
};
</script>

<style scoped>
.about-container {
  max-width: 800px;
  margin: auto;
  padding: 20px;
  text-align: center;
}

.title {
  font-size: 2.5rem;
  margin-bottom: 20px;
}

.about-section, .mission, .team, .contact {
  margin-bottom: 30px;
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
