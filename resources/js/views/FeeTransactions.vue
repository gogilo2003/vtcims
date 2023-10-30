<template>
  <div class="card">
    <table class="table table-striped">
      <thead></thead>
    </table>
  </div>
</template>
<script>
export default {
  data() {
    return {
      fees: [],
    };
  },
  created() {
    axios
      .get(
        "/api/eschool/fees/transactions?" +
          "api_token=" +
          localStorage.getItem("token")
      )
      .then((response) => {
        this.fees = response.data;
      })
      .catch((error) => {
        cosole.log(error);
      });
  },
};
</script>