<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">TikTakToe</div>

                    <div class="card-body">
                        <div class="board">
                            <div class="board-edge clearfix">
                                <div class="board-bondary">
                                    <div class="board-body">
                                        <div v-if="winner" class="text-center p-2">The winner is "{{ winner }}"</div>
                                        <div v-if="!winner && !draw" class="text-center p-2">Now is {{ isPlayerX ? "X" : "O" }} turn</div>
                                        <div v-if="draw" class="text-center p-2">It is a draw!</div>
                                        <div class="board-row">
                                            <div class="square" @click="OnClicked(0)">{{ tics[0] }}</div>
                                            <div class="square" @click="OnClicked(1)">{{ tics[1] }}</div>
                                            <div class="square" @click="OnClicked(2)">{{ tics[2] }}</div>
                                        </div>
                                        <div class="board-row">
                                            <div class="square" @click="OnClicked(3)">{{ tics[3] }}</div>
                                            <div class="square" @click="OnClicked(4)">{{ tics[4] }}</div>
                                            <div class="square" @click="OnClicked(5)">{{ tics[5] }}</div>
                                        </div>
                                        <div class="board-row">
                                            <div class="square" @click="OnClicked(6)">{{ tics[6] }}</div>
                                            <div class="square" @click="OnClicked(7)">{{ tics[7] }}</div>
                                            <div class="square" @click="OnClicked(8)">{{ tics[8] }}</div>
                                        </div>
                                        <div class="text-center p-2"><button @click="reset()">Reset</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        <div v-html="log"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            tics: Array(9).fill(""),
            isPlayerX: true,
            winner: null,
            log: "",
            counter: 0,
            draw: false
        }
    },
    methods: {
        calculateWinner() {
            const lines = [
                [0, 1, 2],
                [3, 4, 5],
                [6, 7, 8],
                [0, 3, 6],
                [1, 4, 7],
                [2, 5, 8],
                [0, 4, 8],
                [2, 4, 6]
            ];
            for (let i = 0; i < lines.length; i++) {
                const [a, b, c] = lines[i];
                if (this.tics[a] && this.tics[a] === this.tics[b] && this.tics[b] === this.tics[c]) {
                    this.winner = this.tics[a];
                    // console.log(this.winner + " is a winner");
                    var info = this.winner + " is a winner";
                    axios
                        .post('/api/game/reset',{info: info}).then((response)=>{
                            // console.log(response)
                        }).catch((error)=>{
                            console.log(error.response.data)
                        });
                    return;
                }
                
            }
            if(this.counter == 9 && !this.winner) {
                this.draw = true;
                console.log("It's a draw!");
                    axios
                        .post('/api/game/reset',{info: 'It is a draw!'}).then((response)=>{
                            // console.log(response)
                        }).catch((error)=>{
                            console.log(error.response.data)
                        });
            }
    },
    OnClicked(i) {
        this.counter++;
        
        if (this.winner === "X" || this.winner === "O") {
            return;
        }
        if (this.tics[i] === "X" || this.tics[i] === "O") {
            return;
        }
        this.$set(this.tics, i, this.isPlayerX ? "X" : "O");
        // console.log(this.tics[i] + " is on square "+ (i+1));
        var info = this.tics[i] + " is on square "+ (i+1);
        if (this.counter === 1){
        axios
            .post('/api/game/store',{counter: this.counter, player: this.tics[i], square: i+1}).then((response)=>{
                // console.log(response)
            }).catch((error)=>{
                console.log(error.response.data)
            });
        
        } else {
            
        axios
            .post('/api/match/store',{player: this.tics[i], square: i+1}).then((response)=>{
                // console.log(response)
            }).catch((error)=>{
                console.log(error.response.data)
            });
        }
        
      this.isPlayerX = !this.isPlayerX;
      this.calculateWinner();
      this.loadLog();
    },
    loadLog(){
        console.log('loading log');
        axios
            .post('/api/match/get').then((response)=>{
                // console.log(response.data);
                this.log = "";
                for(let i =0; i < response.data.length; i++){
                    if (response.data[i].log){
                        this.log += response.data[i].log;
                        this.log += "<br>";
                    }    
                }
                }).catch((error)=>{
                    console.log(error.response.data)
                });
    },
    reset() {
        console.log("reset");
        for (let i = 0; i < this.tics.length; i++) {
            this.$set(this.tics, i, "");
        }
        this.winner = null;
        this.draw = false;
        this.counter = 0;
        this.log = "";
        axios
            .post('/api/game/reset',{info: 'Reset'}).then((response)=>{
                // console.log(response)
            }).catch((error)=>{
                console.log(error.response.data)
            });
    }
  }
};
</script>

<style scoped>
.board-row {
  clear: both;
  display: table;
}
.board-bondary {
  position: relative;
  float: left;
  left: 50%;
}
.board-body {
  position: relative;
  left: -50%;
}
.clearfix::after {
  content: "";
  display: block;
  height: 0;
  clear: both;
  visibility: hidden;
}
.square {
  background: #fff;
  border: 2px solid #999;
  float: left;
  font-size: 48px;
  font-weight: bold;
  line-height: 68px;
  width: 68px;
  height: 68px;
  margin-right: -2px;
  margin-top: -2px;
  padding: 0;
  text-align: center;
}
</style>
