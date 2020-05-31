<script>
import algoliasearch from "algoliasearch/lite";
import "instantsearch.css/themes/algolia-min.css";
import { history } from "instantsearch.js/es/lib/routers";
import { simple } from "instantsearch.js/es/lib/stateMappings";

export default {
  props: ["data"],

  data() {
    return {
      searchClient: algoliasearch(this.data.appId, this.data.searchKey),
      routing: {
        router: history(),
        stateMapping: simple()
      },
    };
  }
};
</script>

<style>
body {
  font-family: sans-serif;
  padding: 1em;
}
.ais-RefinementList-list{
    margin-left: 1em;
}
.ais-RefinementList-list .ais-RefinementList-item:nth-child(1){
    margin-top: 1em;
}
div.ais-Pagination{
    margin-top: 1em;
}
</style>