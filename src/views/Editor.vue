<template>
  <div id="app">
    <div class="drag">
      <h2>Components</h2>
      <draggable id="components" :list="listComponents" :move="moveComponent">
        <transition-group>
          <div v-for="element in listComponents" :key="element.id">
            <h3>{{ element.name }}</h3>
          </div>
        </transition-group>
      </draggable>
      <h2>Content</h2>
      <draggable id="content" :list="listContent">
        <transition-group>
          <div v-for="element in listContent" :key="element.id">
            <h3>{{ element.name }}</h3>
          </div>
        </transition-group>
      </draggable>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";

export default {
  name: "Editor",
  components: {
    draggable,
  },
  data() {
    return {
      listComponents: [
        { id: 1, name: "carousel", html: "Html code" },
        { id: 2, name: "image", html: "Html image code" },
      ],
      listContent: [],
    };
  },
  methods: {
    moveComponent(evt) {
      let componentID = evt.draggedContext.element.id;
      console.log(componentID);
      console.log(evt);
      this.listContent.push(evt.draggedContext.element);
    },
  },
};
</script>

<style scoped>
.drop-zone {
  background-color: #eee;
  margin-bottom: 10px;
  padding: 10px;
}

.drag-el {
  background-color: #fff;
  margin-bottom: 10px;
  padding: 5px;
}
h2 {
  border-bottom: 1px black solid;
}
</style>
