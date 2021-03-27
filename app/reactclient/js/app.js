import React from 'react'
import ReactDOM from 'react-dom'
import {Provider} from 'react-redux'
import store from './tools/store'
import GeoMap from './components/GeoMap'
import ParkInfo from './components/ParkInfo'
import {loadData} from './tools/store-slice'

//To send request
//To load data by AJAX
store.dispatch(loadData())


ReactDOM.render(
    <Provider store={store}>
        <div id="container">
            <GeoMap />

            <ParkInfo />
        </div>
    </Provider>,
    document.getElementById('app')
)