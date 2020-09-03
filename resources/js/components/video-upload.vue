<template>
    <div>
        <file-pond
            name="video"
            ref="pond"
            label-idle="Drop files here..."
            v-bind:allow-multiple="false"
            @init="handleFilePondInit"
        />

        <button
            @click="
                createVideo({
                    name: 'sample.mp4',
                    size: 1024,
                    type: 'video/mp4',
                })
            "
        >
            Create Video
        </button>

        <pre>{{ url }}</pre>
    </div>
</template>

<script>
import vueFilePond, { setOptions } from "vue-filepond";
import axios from "axios";
import "filepond/dist/filepond.min.css";

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFileMetadata from "filepond-plugin-file-metadata";

// Create component
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    FilePondPluginFileMetadata
);

export default {
    name: "VideoUpload",

    components: { FilePond },

    data: () => ({
        url: null,
    }),

    created: async function () {
        const self = this;

        setOptions({
            server: {
                url: this.url,
                async process(
                    fieldName,
                    file,
                    metadata,
                    load,
                    error,
                    progress,
                    abort
                ) {
                    const { data } = await axios.post(self.preSignedUrl, {
                        file_name: file.name,
                    });

                    const { url } = data.data.request;

                    const form = new FormData();

                    form.append("file", file);

                    await axios.put(url, form, {
                        onUploadProgress(progressEvent) {
                            progress(
                                progressEvent.lengthComputable,
                                progressEvent.loaded,
                                progressEvent.total
                            );
                        },
                    });

                    await self.createVideo(file);

                    load(file.name);
                },
            },
        });
    },

    props: {
        preSignedUrl: {
            type: String,
            required: false,
            default: "/video-transcoder/pre-signed-url",
        },
    },

    computed: {},

    methods: {
        handleFilePondInit() {},

        async createVideo(file) {
            const { data } = await axios.post("/video-transcoder/videos", {
                file: {
                    name: file.name,
                    size: file.size,
                    type: file.type,
                },
            });
        },
    },
};
</script>
