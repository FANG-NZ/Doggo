import {configureStore} from '@reduxjs/toolkit'
import MapDataReducer from './store-slice'

export default configureStore({
    reducer:{
        'MapData' : MapDataReducer
    }
})