import {createSlice, createAsyncThunk} from '@reduxjs/toolkit'
import { Client } from './client'
import "regenerator-runtime/runtime";

const initialState = {
    'data' : [],
    'displayData' : [],
    'offLeashShown' : true,
    'onLeashShown' : true,

    'showParkInfo' : false,
    'selectedPark' : null
}

export const getDisplayData = state => state.MapData.displayData


/**
 * define the thunk for async load menu data
 */
 export const loadData = createAsyncThunk(
    'MapData/loadData',

    async () => {
        const response = await Client.get("api/park/load")
        return response
    }
)

const MapDataSlice = createSlice({
    name: 'MapData',
    initialState,
    reducers:{

        /**
         * Function is to setup data
         * @param {*} state 
         * @param {*} action 
         * @returns 
         */
        setData(state, action){
            return {...state, ...action.payload}
        },


        /**
         * Action is to handle leash changed
         */
        onLeashChanged: {

            reducer(state, action){

                state = {...state, ...action.payload}

                //For display both
                if(state.onLeashShown && state.offLeashShown){
                    state.displayData = state.data
                    return state
                }

                //For hide both
                if(!state.onLeashShown && !state.offLeashShown){
                    state.displayData = []
                    return state
                }
    
                let _display
                if(state.onLeashShown){
                    _display = state.data.filter(item => item.is_leash_on === true)
                }
                else{
                    _display = state.data.filter(item => item.is_leash_on === false)
                }
                
                state.displayData = _display
                return state
            },
            prepare(data){
                const {name , value} = data

                if(name === "off"){
                    return{
                        payload:{
                            offLeashShown: value
                        }
                    }
                }

                return{
                    payload:{
                        onLeashShown: value
                    }
                }
            }

        },
        
        /**
         * Function is to open ParkInfo
         * @param {*} state 
         * @param {*} action 
         */
        openParkInfo(state, action){
            state.showParkInfo = true
            state.selectedPark = action.payload
        },

        /**
         * Function is to close ParkInfo 
         * @param {*} state 
         * @param {*} action 
         */
        closeParkInfo(state, action){
            state.showParkInfo = false
            state.selectedPark = null
        }
    },

    extraReducers: {

        //Handle reset load data
        [loadData.fulfilled]: (state, action) => {
            const {data} = action.payload

            state.data = data
            state.displayData = data
            return state
        }
    }
})

export const {setData, onLeashChanged, openParkInfo, closeParkInfo} = MapDataSlice.actions
export default MapDataSlice.reducer