import React from 'react'
import {Marker} from 'react-map-gl'
import {useDispatch} from 'react-redux'
import {openParkInfo} from '../tools/store-slice'

import dogOffLeashImg from '../../../../public/dog-off-leash.png'
import dogOnLeashImg from '../../../../public/dog-on-leash.png'


const MapMarkers = (props) => {
    const {data, onClick} = props
    const _dispatch = useDispatch()


    return data.map((item, index) => {
        //setup image icon
        let _img = item.is_leash_on ? dogOnLeashImg : dogOffLeashImg
        
        return (
            <Marker key={`marker-${index}`}  longitude={item.latlng.latitude} latitude={item.latlng.longitude}
            >
                <img src={_img} 
                    style={{width:'40px', height: '40px', cursor: "pointer"}}
                    onClick={() => {
                        _dispatch(openParkInfo(item))
                    }}
                />
            </Marker>
        )
    })
}

export default MapMarkers