import React,{useEffect,useState} from "react";
import {
  Text,
  SafeAreaView,
  View,
  ScrollView,
  StyleSheet,
  TouchableOpacity,
  Modal,
  TextInput
} from "react-native";
import axios from "axios";
const App=()=>{

  const [list,setList]=useState([])
  const [modalVisitor,setModalVisitor]=useState(false)

  const [id,setId]=useState(null);
  const [numero,setNumero]=useState("");
  const [nom,setNom]=useState("");
  const [nbJours,setNbJours]=useState(0);
  const [tarifJournalier,setTarifJournalier]=useState(0);

  useEffect(()=>{
    getVisitorsList();
  },[])

  const getVisitorsList=()=>{
    fetch("http://127.0.0.1:8000/api/visitors",{
      method:"GET",
      headers:{
        'Accept':'application/json',
        'Content-Type':'application/json'
      },
    }).then(res=>{
      return res.json()
    }).then(res=>{
      console.log(res);
      if (res) {
        setList(res)
      }
    }).catch(err=>{
      console.log(err);
    })
  }

  const handleCreate=()=>{
    setModalVisitor(true)
  }
  const handleCloseModal=()=>{
    setModalVisitor(false)
  }
  const handleSave=()=>{
    if (id==null) {
      fetch("http://127.0.0.1:8000/api/visitors",{
        method:"POST",
        headers:{
          'Accept':'application/json',
          'Content-Type':'application/json'
        },
        body:JSON.stringify({
          "numero":numero,
          "nom":nom,
          "nbJours":parseInt(nbJours),
          "tarifJournalier":parseFloat(tarifJournalier),
        })
      }).then(res=>{
        return res.json()
      }).then(res=>{
       getVisitorsList();
       setModalVisitor(false)
       clearForm();
       console.log(res);
      }).catch(err=>{
        console.log(err);
      })
    } else {
      // fetch("http://127.0.0.1:8000/api/visitors",{
      //   method:"PUT",
      //   headers:{
      //     'Accept':'application/json',
      //     'Content-Type':'application/json'
      //   },
      //   body:JSON.stringify({
      //     "id":id,
      //     "numero":numero,
      //     "nom":nom,
      //     "nbJours":parseInt(nbJours),
      //     "tarifJournalier":parseFloat(tarifJournalier),
      //   })
      // })
      axios.put("http://127.0.0.1:8000/api/visitors",
          JSON.stringify({
          "id":id,
          "numero":numero,
          "nom":nom,
          "nbJours":parseInt(nbJours),
          "tarifJournalier":parseFloat(tarifJournalier),
        },
        {
              'Accept':'application/json',
              'Content-Type':'application/json'
            }
        )
      )
      .then(res=>{
        return res.json()
      }).then(res=>{
       getVisitorsList();
       setModalVisitor(false)
       clearForm();
       console.log(res);
      }).catch(err=>{
        console.log(err);
      })
    }
  
  }
  const clearForm=()=>{
    setId(null)
    setNumero("");
    setNom("");
    setNbJours(0);
    setTarifJournalier(0);
  }

  const handleEdit=(item)=>{
    setId(item.id);
    setNumero(item.numero);
    setNom(item.nom);
    setNbJours(item.nbJours);
    setTarifJournalier(item.tarifJournalier);
    setModalVisitor(true)
  }
/*TODO:405 method not allowed*/
  const handleRemove=(item)=>{
    fetch("http://127.0.0.1:8000/api/visitors",{
      method:"DELETE",
      headers:{
        'Accept':'application/json',
        'Content-Type':'application/json'
      },
      body:JSON.stringify({
        "id":item.id
      })
    }).then(res=>{
      return res.json()
    }).then(res=>{
     getVisitorsList();
     console.log(res);
    }).catch(err=>{
      console.log(err);
    })
  }

  return (
    <SafeAreaView>
      <Modal
      /*JUST FOR TESTING*/
      // visible={true}
      visible={modalVisitor}
      >
        <SafeAreaView>
          <View style={[styles.rowBetween,{paddingHorizontal:10}]}>
            <Text style={styles.txtClose}>Nouveau visiteur</Text>
            <TouchableOpacity onPress={handleCloseModal}>
              <Text style={styles.txtClose}>Fermer</Text>
            </TouchableOpacity>
          </View>
          <View style={{paddingHorizontal:10,marginTop:20}}>
            <Text>Numéro</Text>
            <TextInput
              // placeholder={"Numero"}
              style={styles.txtInput}
              value={numero}
              onChangeText={(text)=>{
                setNumero(text)
              }}
            />

            <Text>Nom</Text>
            <TextInput
              style={styles.txtInput}
              value={nom}
              onChangeText={(text)=>{
                setNom(text)
              }}
            />

            <Text>Nombre de jours</Text>
            <TextInput
              style={styles.txtInput}
              value={nbJours}
              onChangeText={(text)=>{
                setNbJours(text)
              }}
            />

            <Text>Tarif journalier</Text>
            <TextInput
              style={styles.txtInput}
              value={tarifJournalier}
              onChangeText={(text)=>{
                setTarifJournalier(text)
              }}
            />
             <TouchableOpacity onPress={handleSave} style={styles.btnContainer}>
              <Text style={styles.txtClose}>Enregistrer</Text>
            </TouchableOpacity>
          </View>
        </SafeAreaView>
      </Modal>
      <View style={styles.rowBetween}>
        <Text style={styles.txtMain}>Liste des visiteurs ({list.length})</Text>
        <TouchableOpacity style={{padding:10}} onPress={handleCreate}>
          <Text style={{fontWeight:"bold",color:"blue"}}>Nouveau visiteur</Text>
        </TouchableOpacity>
      </View>
      <ScrollView
        contentContainerStyle={{
          paddingHorizontal:10
        }}
      >
        {
          list.map(
              (item,index)=>{
                return (
                 <View key={item.id} style={styles.rowBetween}>
                   <View style={styles.item}>
                      <Text style={styles.txtName}>{item.nom}</Text>
                      <Text style={styles.txtNormal}>Visisteur N° {item.numero}</Text>
                      <Text style={styles.txtNormal}>Nombre de jours : {item.nbJours}</Text>
                      <Text style={styles.txtNormal}>Tarif (journalier) : {item.tarifJournalier}</Text>
                    </View>
                    <View> 
                      <TouchableOpacity onPress={ () => handleRemove(item)}>
                        <Text style={styles.txtDelete}>Supprimer</Text>
                      </TouchableOpacity>
                      <TouchableOpacity onPress={ () => handleEdit(item)}>
                        <Text style={styles.txtEdit}>Modifier</Text>
                      </TouchableOpacity>
                    </View>
                  </View>
                )
              }
          )
        }
      </ScrollView>
    </SafeAreaView>
  );
}
export default App;
const styles=StyleSheet.create({
  txtMain:{
    fontSize:16,
    fontWeight:"bold",
    padding:10
  },
  item:{
   
  },
  txtName:{
    fontSize:16,
    fontWeight:"bold",
  },
  txtNormal:{
    fontSize:14,
    color:"#444"
  },
  txtDelete:{
    fontSize:14,
    color:"red"
  },
  txtEdit:{
    fontSize:14,
    color:"orange"
  },
  rowBetween:{
    flexDirection:"row",
    justifyContent:"space-between",
    paddingVertical:10,
    borderBottomWidth:1,
    borderBottomColor:"#888"
  },
  txtClose:{
    color:"gray",
    fontSize:16,
    fontWeight:"bold"
  },
  txtInput:{
    padding:10,
    borderWidth:1,
    borderColor:"#888",
    marginBottom:10,

  },
  btnContainer:{
    borderWidth:1,
    padding:10,
    borderColor:"gray",
    backgroundColor:"black",
    textAlign:"center",
    justifyContent:"center",
    alignItems:"center"
  }
});