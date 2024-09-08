/*
Copyright 2017 Ziadin Givan

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

https://github.com/givanz/VvvebJs
*/


Vvveb.ComponentsGroup['Embeds'] = ["html/iframe", "embeds/pdf", "embeds/video", "embeds/audio"];

Vvveb.Components.extend("_base", "html/iframe", {
    nodes      : ["iframe"],
    attributes : ["data-component-iframe"],
    name       : "Iframe",
    image      : "icons/file.svg",
    resizable  : true,
    html       : `<div data-component-iframe>
                      <iframe style="width: 100%; height: 480px;" allowfullscreen 
                              sandbox="allow-scripts allow-same-origin allow-popups" 
                              allow=":encrypted-media; :picture-in-picture"></iframe>
                  </div>`,
    properties : [
        {
            name      : "Src",
            key       : "src",
            htmlAttr  : "src",
            child     : "iframe",
            inputtype : TextInput
        }, {
            name      : "Width",
            key       : "width",
            child     : "iframe",
            htmlAttr  : "style",
            inputtype : CssUnitInput
        }, {
            name      : "Height",
            key       : "height",
            child     : "iframe",
            htmlAttr  : "style",
            inputtype : CssUnitInput
        }]
});

Vvveb.Components.extend("_base", "embeds/pdf", {
    nodes      : ["embed"],
    attributes : ["data-component-pdf"],
    name       : "PDF",
    image      : "icons/pdf.svg",
    resizable  : true,
    html       : `<figure data-component-pdf>
                      <embed class="data-component-pdf" src="" width="100%" height="240" />
                  </figure>`,
    properties : [
        {
            name      : "PDF",
            key       : "src",
            htmlAttr  : "src",
            inputtype : PdfInput
        }, {
            name      : "Width",
            key       : "width",
            htmlAttr  : "style",
            inputtype : CssUnitInput
        }, {
            name      : "Height",
            key       : "height",
            htmlAttr  : "style",
            inputtype : CssUnitInput
        }]
});

Vvveb.Components.extend("_base", "embeds/video", {
    nodes      : ["video"],
    attributes : ["data-component-video"],
    name       : "Video",
    image      : "icons/video.svg",
    resizable  : true,
    html       : `<figure data-component-video>
                      <video style="width: 80%; margin: 0 auto;height: 480px;" 
                             playsinline loop autoplay muted controls 
                             src="${wwwroot}/local/kopere_dashboard/_editor/media/sample.webm"></video>
                  </figure>`,
    properties : [
        {
            name      : "Video file",
            key       : "src",
            htmlAttr  : "src",
            inputtype : ImageInput
        }, {
            name      : "Poster file",
            key       : "poster",
            htmlAttr  : "poster",
            inputtype : ImageInput
        }, {
            name      : "Width",
            key       : "width",
            htmlAttr  : "style",
            inputtype : CssUnitInput
        }, {
            name      : "Height",
            key       : "height",
            htmlAttr  : "style",
            inputtype : CssUnitInput
        }, {
            name      : "Muted",
            key       : "muted",
            htmlAttr  : "muted",
            inputtype : CheckboxInput
        }, {
            name      : "Loop",
            key       : "loop",
            htmlAttr  : "loop",
            inputtype : CheckboxInput
        }, {
            name      : "Autoplay",
            key       : "autoplay",
            htmlAttr  : "autoplay",
            inputtype : CheckboxInput
        }, {
            name      : "Plays inline",
            key       : "playsinline",
            htmlAttr  : "playsinline",
            inputtype : CheckboxInput
        }, {
            name      : "Show Controls",
            key       : "controls",
            htmlAttr  : "controls",
            inputtype : CheckboxInput
        }]
});

Vvveb.Components.extend("_base", "embeds/audio", {
    nodes      : ["audio"],
    attributes : ["data-component-audio"],
    name       : "Audio",
    image      : "icons/audio.svg",
    resizable  : true,
    html       : `<figure data-component-audio>
                      <audio controls src=""></audio>
                  </figure>`,
    properties : [
        {
            name      : "Audio File",
            key       : "src",
            child     : "audio",
            htmlAttr  : "src",
            inputtype : ImageInput
        }, {
            name      : "Poster file",
            key       : "poster",
            htmlAttr  : "poster",
            inputtype : ImageInput
        }, {
            key       : "audio_options",
            inputtype : SectionInput,
            name      : false,
            data      : {header : "Options"},
        }, {
            name      : "Autoplay",
            key       : "autoplay",
            htmlAttr  : "autoplay",
            child     : "audio",
            inputtype : CheckboxInput,
            inline    : true,
            col       : 4,
        }, {
            name      : "Loop",
            key       : "loop",
            htmlAttr  : "loop",
            inputtype : CheckboxInput,
            child     : "audio",
            inline    : true,
            col       : 4
        }]
});
