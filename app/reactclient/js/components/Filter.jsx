import React from 'react'
import {useDispatch, useSelector} from 'react-redux'
import { onLeashChanged } from '../tools/store-slice'
import dogOffLeashImg from '../../../../public/dog-off-leash.png'
import dogOnLeashImg from '../../../../public/dog-on-leash.png'

const Filter = () => {
    const _dispatch = useDispatch()
    const _offLeashShown = useSelector(state => state.MapData.offLeashShown)
    const _onLeashShown = useSelector(state => state.MapData.onLeashShown)

    /**
     * Function is to handle changed
     */
    function handleOffLeashChaned(){
        _dispatch(onLeashChanged({'name': "off", 'value': !_offLeashShown}))
    }

    function handleOnLeashChanged(){
        _dispatch(onLeashChanged({'name': "on", 'value': !_onLeashShown}))
    }

    return(
        <div id="filter">
            <label htmlFor="offLeash">
                <input
                    type="checkbox"
                    value="Off"           
                    name="filterFeatureOnOffLeash"
                    id="offLeash" 
                    defaultChecked="checked"
                    onChange={() => handleOffLeashChaned()}
                />
                <img src={dogOffLeashImg} height="32" />
                Off-leash
            </label>

            <label htmlFor="onLeash">
                <input
                    type="checkbox"
                    value="On"
                    name="filterFeatureOnOffLeash"
                    id="onLeash" 
                    defaultChecked="checked"
                    onChange={() => handleOnLeashChanged()}
                />
                <img src={dogOnLeashImg} height="32" />
                On-leash
            </label>
        </div>
    )
}

export default Filter