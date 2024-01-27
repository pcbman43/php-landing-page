<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta viewport="width=device-width, initial-scale=1.0">
    <title>Link Submission Form</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        .message {
            margin-top: 10px;
            color: red;
            /* Default color for error */
            padding: 5px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div id="app">
        <ul>
            <li><a href="https://typeracer.com" target="_blank">typeracer.com</a></li>
            <li><a href="https://keybr.com" target="_blank">keybr.com</a></li>
            <li><a href="https://monkeytype.com" target="_blank">monkeytype.com</a></li>
            <li><a href="https://zty.pe" target="_blank">zty.pe</a></li>
        </ul>

        <h1>Submit a Link</h1>

        <form @submit.prevent="submitForm">
            <label for="link">Link:</label>
            <input type="text" id="link" v-model="link" required>

            <!-- Combined message div for error/loading -->
            <div v-if="isLoading || responseMessage.startsWith('Error:')" class="message">
                {{ message }}
            </div>

            <button type="submit">Submit</button>
        </form>


        <!-- Display links as a table using Vue -->
        <h2>Typeracer custom race links</h2>
        <div v-if="links.length > 0">
            <table>
                <tr>
                    <th>Author's Name</th>
                    <th>Link</th>
                    <th>Posted on</th>
                </tr>
                <tr v-for="link in links" :key="link.id">
                    <td>{{ link.authorName }}</td>
                    <td><a :href="link.link" target="_blank">{{ link.link }}</a></td>
                    <td>{{ link.timestamp }}</td>
                </tr>
            </table>
        </div>
        <div v-else>
            <p>No recent links submitted.</p>
        </div>
    </div>

    <!-- Include Vue 3 from CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <script>
        Vue.createApp({
            data() {
                return {
                    link: 'https://play.typeracer.com?rt=uimrjp66y',
                    responseMessage: '',
                    links: [], // This will hold the links fetched from the server
                    isLoading: false
                };
            },
            computed: {
                message() {
                    if (this.isLoading) {
                        return "Loading...";
                    } else if (this.responseMessage.startsWith('Error:')) {
                        return this.responseMessage;
                    }
                    return '';
                }
            },
            methods: {
                async submitForm() {
                    this.isLoading = true; // Start loading
                    try {
                        const response = await fetch('process_form.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                link: this.link
                            }).toString()
                        });

                        if (!response.ok) {
                            throw new Error('Server responded with an error.');
                        }

                        const data = await response.text();
                        this.responseMessage = data;
                        await this.fetchLinks(); // Refresh the links after a successful submission
                    } catch (error) {
                        this.responseMessage = 'Failed to submit link: ' + error.message;
                    } finally {
                        this.isLoading = false; // Stop loading regardless of success or error
                    }
                },

                async fetchLinks() {
                    this.isLoading = true; // Start loading
                    try {
                        const response = await fetch('fetch_links.php');
                        if (!response.ok) {
                            throw new Error('Failed to fetch links.');
                        }

                        const links = await response.json();
                        this.links = links;
                    } catch (error) {
                        console.error('Error fetching links:', error);
                    } finally {
                        this.isLoading = false; // Stop loading regardless of success or error
                    }
                }
            },

            mounted() {
                this.fetchLinks();
            }
        }).mount('#app');
    </script>

</body>

</html>