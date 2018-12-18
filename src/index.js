import React from 'react';
import ReactDOM from 'react-dom';
import App from './App';

import {BrowserRouter} from 'react-router-dom';

//bulma css
import 'bulma/css/bulma.min.css';
import './index.css';

ReactDOM.render(
    <BrowserRouter>
        <App/>
    </BrowserRouter>,
    document.getElementById('root')
);

console.log('%c Stop!', 'color: red; font-size: 30px; font-weight: bold;');
console.log('%c This is a feature for developers. Tempering with the scripts on this page might affect the security of your account!', 'color: black; font-size: 16px; font-weight: bold;');

