import React, {Component} from 'react';
import Editor, {createEditorStateWithText} from 'draft-js-plugins-editor';
import createToolbarPlugin from 'draft-js-static-toolbar-plugin';

import 'draft-js-static-toolbar-plugin/lib/plugin.css';
import './Editor.css';

//editor toolbar
const toolbarPlugin = createToolbarPlugin();
const {Toolbar} = toolbarPlugin;

export default class EditorComponent extends Component {

    state = {
        editorState: createEditorStateWithText('Write your article here.')
    };

    render() {
        const {editorState} = this.state;

        return (
            <div>
                <div className="field">
                    <Toolbar/>
                    <div className="editor">
                        <Editor editorState={editorState} onChange={state => this.setState({editorState: state})}
                                plugins={[toolbarPlugin]}/>

                    </div>
                </div>
            </div>
        )
    }

}
