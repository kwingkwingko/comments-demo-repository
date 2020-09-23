<template>
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded shadow-sm p-8 mb-4">
            <div class="mb-4">
                <h4 class="text-black"><b>Kwing</b></h4>
                <span class="text-black">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span>
            </div>
            <div class="mb-4 bg-white rounded shadow-sm p-8">
                <img src="https://bit.ly/3hTyOPy"/>
            </div>
            <hr/>
            <div class="mb-4">
                Comments
            </div>
            <textarea v-show="comments.length <= 2" v-model="data.body" v-on:keyup="checkIfEnterKey"
                      placeholder="Write a comment..."
                      class="bg-secondary rounded text-white leading-normal resize-none w-full py-2 px-3"
                      :class="[state === 'editing' ? 'h-24' : 'h-10']"
                      @focus="startEditing"
                      @blur="stopEditing">
            </textarea>
            <comment v-for="(comment, index) in comments"
                     :key="comment.id"
                     :user="user"
                     :comment="comment"
                     :class="[index === comments.length - 1 ? '' : 'mb-6']">
            </comment>
        </div>
    </div>
</template>

<script>
    import comment from './CommentItem'
    export default {
        components: {
            comment
        },
        props: {
            user: {
                required: true,
                type: Object,
            }
        },
        data: function() {
            return {
                state: 'default',
                data: {
                    body: ''
                },
                comments: []
            }
        },
        created() {
            this.fetchComments();
        },
        methods: {
            startEditing() {
                this.state = 'editing';
            },
            stopEditing() {
                this.state = 'default';
                this.data.body = '';
            },
            fetchComments() {
                const t = this;

                axios.get('/comments')
                    .then(({data}) => {
                        t.comments = data;
                        console.log(t.comments);
                    })
            },
            checkIfEnterKey: function(e) {
                if (e.keyCode === 13) {
                    this.data.header_id = 0;
                    const t = this;

                    axios.post('/comments', t.data)
                        .then(({data}) => {
                            t.comments.unshift(data);
                            t.stopEditing();
                        })
                }
            },
            commentIndex(commentId) {
                return this.comments.findIndex((element) => {
                    return element.id === commentId;
                });
            }
        }
    }
</script>