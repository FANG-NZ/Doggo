import React, {useState} from 'react'
import ImageUploading from 'react-images-uploading'
import {useSelector, useDispatch} from 'react-redux'
import {closeParkInfo} from '../tools/store-slice'
import {Client} from '../tools/client'

const ParkInfo = () => {
    const _isShown = useSelector(state => state.MapData.showParkInfo)
    const _park = useSelector(state => state.MapData.selectedPark)
    const _dispatch = useDispatch()

    const [images, setImages] = React.useState();

    /**
     * Function is to handle upload image
     * @param {} image 
     */
     function handleUploadImage(images){
        setImages(images)

        let formData = new FormData()
        formData.append('image', images[0]['file'])

        const _config = {
            method: "POST",
            body: formData,
            headers: {}
        }
        const _url = "api/park/upload-image/" + _park.id

        window.fetch(_url, _config).then(
            response => {
                console.log(response)
            }
        )
        
    }

    return(
        <React.Fragment>
            {_park &&
                <div id="park-info">
                    <a href="/" 
                        onClick={(e) => { 
                            e.preventDefault()
                            _dispatch(closeParkInfo())
                        }}
                    >Close</a>
                    <h2>{_park.title}</h2>

                    <ul className="park__features">
                        <li>{_park.leash_note}</li>
                    </ul>

                    <div className="live-image">
                        <p>{_park.live_image}</p>
                        {_park.live_image
                            ? <img src={_park.live_image} width="auto" height='100' />   
                            : <strong>NO IMAGE UOPLOADED</strong> 
                        }
                    </div>

                    <div className="image-upload">

                        {
                            _park.has_pending_image &&
                            <p className="warning">One image is waiting for approve</p>
                        }

                        <ImageUploading
                            value={images}
                            multiple={false}
                            onChange={handleUploadImage}
                            acceptType={['jpg', 'png', 'jpeg']}
                        >
                            {({
                                imageList,
                                onImageUpload,
                                dragProps,
                                isDragging,
                                maxNumber="1"
                            }) => (
                                <>
                                {imageList.length > 0 &&
                                    <img src={imageList[0]['dataURL']} alt="" width="auto" height='100'/>
                                }
                                
                                <button
                                    style={isDragging ? { color: 'red' } : undefined}
                                    onClick={onImageUpload}
                                    {...dragProps}
                                    >
                                    Click or Drop here
                                </button>
                                </>
                            )}
                            
                        </ImageUploading>
                    </div>

                    <p className="park__notes">{_park.notes}</p>
                    <p className="park__provider">Managed by <strong>{_park.provider}</strong></p>
                </div>
            }
        </React.Fragment>
    )
}

export default ParkInfo