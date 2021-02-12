const axiosPath = 'http://localhost/facial/server/index.php'
const video = document.getElementById("video")
const container = document.getElementById("containerVideo")
const DOMtitle = document.getElementById("title")
const DOMmessage = document.getElementById("message")
const DOMname = document.getElementById("name")


const tiempo = 10000

function startvideo() {
    navigator.getUserMedia = (
        navigator.getUserMedia ||
        navigator.webkitGetUserMedia ||
        navigator.mozGetUserMedia ||
        navigator.msGetUserMedia
    )

    navigator.getUserMedia(
        { video: {} },
        stream => video.srcObject = stream,
        err => console.log('error video', err)
    )
}


const routeModels = '/facial/js/models'
Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri(routeModels),
    faceapi.nets.faceLandmark68Net.loadFromUri(routeModels),
    faceapi.nets.faceRecognitionNet.loadFromUri(routeModels)
]).then(startvideo).catch(() => 'error')



const requestPhotos = async () => {
    const res = await axios.get('http://localhost/facial/server/index.php', {
        params: {
            type: 'get_photos'
        }
    }).then(res => res)

    return res.data
}
const detectionFace = async () => {
    await faceapi.loadSsdMobilenetv1Model(routeModels)
    const canvas = faceapi.createCanvasFromMedia(video)
    container.append(canvas)
    const displaySize = { width: video.width, height: video.height }
    faceapi.matchDimensions(canvas, displaySize)
    let fullFaceDescriptions = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors()
    const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())

    const labels = await requestPhotos()
    console.log('labels -> ', labels)

    const labeledFaceDescriptors = await Promise.all(
        labels && labels.map(async label => {
            console.log(':::::::::: ', label)
            const img = await faceapi.fetchImage(label)

            // detect the face with the highest score in the image and compute it's landmarks and face descriptor
            const fullFaceDescription = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor()

            if (!fullFaceDescription) {
                throw new Error(`no faces detected for ${label}`)
            }
            const faceDescriptors = [fullFaceDescription.descriptor]
            return new faceapi.LabeledFaceDescriptors(label, faceDescriptors)
        })
    );
    const maxDescriptorDistance = 0.6
    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, maxDescriptorDistance)
    const results = fullFaceDescriptions.map(fd => faceMatcher.findBestMatch(fd.descriptor))

    if (results.length > 0) {
        if (results[0].label !== 'unknown') {
            const nameExtension = results[0].label.split('/')
            const name = nameExtension[nameExtension.length - 1]
            Recognition(name.split('.')[0])
            return
        } else {
            notRrecognition()
            return
        }
    } else {
        notRrecognition()
    }
}

video.addEventListener('play', detectionFace)


function Recognition(data) {
    video.pause()
    DOMtitle.innerHTML = "Usurio detectado"
    const user = data.split('-')
    // Obtine los datos del docente en las dos tablas
    axios.get(axiosPath, {
        params: {
            type: 'get_user_detected',
            id: user[1]
        }
    }).then(resDetected => {
        const dataUser = resDetected.data

        // Revisa si el usuario existe en la tabla current
        axios.get(axiosPath, {
            params: {
                type: 'get_user_current',
                id: user[1],
            }
        }).then(res => {
            // console.log('res:: ', res)
            // El usuario si existe en la tabla current es su salida
            if (res.data.length > 0) {


                if (res.data[0].user_base === "1") {
                    // Guardar los datos en la tabal reporteB e incidenciaB
                    const userAllDate = dataUser[0]
                    const usercurrentData = res.data[0]

                    const dataReport = {
                        num_tarjetaB: userAllDate.num_tarjetaB,
                        periodoIB: usercurrentData.fecha,
                        periodoFB: usercurrentData.fecha,
                        nombreB: userAllDate.nombreB,
                        departamentoB: userAllDate.departamentoB,
                        fechaB: usercurrentData.fecha,
                        H_entradaB: usercurrentData.entrada,
                        estadoB: usercurrentData.estado,
                    }

                    const dataIncidence = {
                        num_tarjetaB: userAllDate.num_tarjetaB,
                        periodoIB: usercurrentData.fecha,
                        periodoFB: usercurrentData.fecha,
                        nombreB: userAllDate.nombreB,
                        departamentoB: userAllDate.departamentoB,
                        fechaB: usercurrentData.fecha,
                        H_entradaB: usercurrentData.entrada,
                        estadoB: usercurrentData.estado,
                        incidenciaB: usercurrentData.incidencia,
                        notaB: usercurrentData.nota,
                    }



                    // Save Reporte docente Base
                    axios.post(axiosPath, {
                        type: 'save_reporte_user_b',
                        ...dataReport,
                    }).then(res => {

                        // save incidencia Base
                        axios.post(axiosPath, {
                            type: 'save_incidencia_user_b',
                            ...dataIncidence,
                        }).then((resIncidence => {

                            // delete user frmo current table
                            axios.delete(axiosPath, {
                                params: {
                                    type: 'delete_user_current',
                                    id: userAllDate.num_tarjetaB,
                                }
                            }).then(resDelete => {
                                DOMmessage.innerHTML = "Salida Exitosa"
                                DOMname.innerHTML = user[0]
                                setTimeout(() => {
                                    video.play()
                                }, tiempo);
                            }).catch(errDelete => {
                                console.log('deleet failed -> ', errDelete)

                            })


                        }))

                    }).catch((err) => {
                        console.log('errr report :: ', err)
                    })

                } else {
                    // Save Reporte docente honorarios
                    // axios.post(axiosPath, {
                    //     type: 'save_reporte_user_h',
                    //     F_nacimientoH: user[1],

                    // }).then(res => {
                    //     console.log('res: ', res)
                    // }).catch((err) => {
                    //     console.log('errr:: ', err)
                    // })
                    // console.log('reporte honorarios')
                }

            } else {
                // El usuario no existe en la tabla current y es su entrada

                axios.post(axiosPath, {
                    type: 'save_user_current',
                    id: user[1],
                    modalidad: dataUser[0].modalidad === 'base' ? 1 : 0,
                    userEntrada: dataUser[0].H_entradaB

                }).then((resSave) => {

                    DOMmessage.innerHTML = "Entrada Exitosa"
                    DOMname.innerHTML = user[0]
                    setTimeout(() => {
                        DOMtitle.innerHTML = "Usurio detectado"
                        DOMmessage.innerHTML = "Entrada Exitosa"
                        DOMname.innerHTML = user[0]
                        video.play()
                    }, tiempo);

                })
            }
        })
    })




}


function notRrecognition() {
    detectionFace()
}