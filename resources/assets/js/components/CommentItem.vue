<template>
    <div>
        <div class="bg-dark rounded leading-normal resize-none w-full py-2 px-3">
            <div class="text-white leading-normal text-sm">
                <b>Kwing </b><span class="mx-1 text-xs">&bull;</span> {{ comment.created_at}}
            </div>
            <div class="flex justify-between mb-1">
                <p class="text-white leading-normal">{{comment.body}}</p>
            </div>
        </div>
        <br>
        <div class="px-5">
            <div v-for="(reply, index) in replies" 
                :key="reply.id"
                :class="[index === replies.length - 1 ? '' : 'mb-6']"
                class="bg-dark rounded leading-normal resize-none w-full py-2 px-3">
                <div class="text-white leading-normal text-sm">
                    <b>Kwing </b><span class="mx-1 text-xs">&bull;</span> {{ reply.created_at}}
                </div>
                <div class="flex justify-between mb-1">
                    <p class="text-white leading-normal">{{reply.body}}</p>
                </div>
            </div>
            <textarea v-show="replies.length <= 2" v-model="data.reply" v-on:keyup="checkIfEnterKey"
                      placeholder="Write a reply..."
                      class="bg-secondary rounded text-white leading-normal resize-none w-full py-2 px-3"
                      :class="[state === 'replying' ? 'h-24' : 'h-10']"
                      @focus="startReplying"
                      @blur="stopReplying">
            </textarea>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            user: {
                required: true,
                type: Object,
            },
            comment: {
                required: true,
                type: Object,
            }
        },
        data: function() {
            return {
                state: 'default',
                data: {
                    id: this.comment.id,
                    body: this.comment.body,
                    reply: ''
                },
                comments: [],
                replies:[],
          }
        },
        created() {
            this.fetchReplies();
        },
        methods: {
            startReplying() {
                this.state = 'replying';
                this.data.header_id = this.data.id;
            },
            checkIfEnterKey: function(e) {
                if (e.keyCode === 13) {
                    this.state = 'default';
                    this.data.body = this.data.reply
                    const t = this;

                    axios.post('/comments', t.data)
                        .then(({data}) => {
                            t.stopReplying();
                            t.fetchReplies();
                        })
                }
            },
            fetchReplies() {
                console.log(this.data);
                const t = this;
                axios.post('/replies/', t.data)
                    .then(({data}) => {
                        t.replies = data;
                    })
            },
            stopReplying() {
                this.state = 'default';
                this.data.reply = '';
                this.data.header_id = 0;
            },
        }
    }
</script>