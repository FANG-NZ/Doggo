import React, {useRef, useEffect, useState} from 'react'
import ReactMapGL from 'react-map-gl'
import MapMarkers from './MapMarkers'
import Filter from './Filter'
import {useSelector} from 'react-redux'
import {getDisplayData} from '../tools/store-slice'

const MAPBOX_TOKEN = 'pk.eyJ1IjoiZmFuZ256IiwiYSI6ImNrbW9ncWh2djBoMm8ydmxoNmxybHJpZXoifQ.Jg-zFYRLVwwGGZoxY6IygQ'
const defaultLng = 174.87
const defaultLat = -41.234

/**
 * define the GeoMap
 * @returns 
 */
const GeoMap = () => {

    const mapData = useSelector(getDisplayData)

    const [viewport, setViewport] = useState({
        latitude: defaultLat,
        longitude: defaultLng,
        zoom: 11
    });

    return(
        <React.Fragment>
            <ReactMapGL
                className="map-container"
                {...viewport}
                width="100%"
                height="100%"
                mapStyle='mapbox://styles/mapbox/streets-v11'
                onViewportChange={nextViewport => setViewport(nextViewport)}
                mapboxApiAccessToken={MAPBOX_TOKEN}
            >
                {mapData.length > 0 &&
                    <MapMarkers data={mapData} />
                }
            </ReactMapGL>

            <Filter />
        </React.Fragment>
    )
}

export default GeoMap