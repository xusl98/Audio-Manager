var urlCancion = "";




window.onload = function () {
  
this.urlCancion = "../music/" + window.name;
  const canvas = document.getElementById("canvas");
  const h3 = document.getElementById('name')
  const audio = document.getElementById("audio");

  const files = this.files; // FileList containing File objects selected by the user (DOM File API)

  h3.innerText = window.name;// Sets <h3> to the name of the file

  ///////// <CANVAS> INITIALIZATION //////////
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
  const ctx = canvas.getContext("2d");
  audio.src = this.urlCancion;
  ///////////////////////////////////////////


  const context = new AudioContext(); // (Interface) Audio-processing graph
  let src = context.createMediaElementSource(audio); // Give the audio context an audio source,
  // to which can then be played and manipulated
  const analyser = context.createAnalyser(); // Create an analyser for the audio context

  src.connect(analyser); // Connects the audio context source to the analyser
  analyser.connect(context.destination); // End destination of an audio graph in a given context
  // Sends sound to the speakers or headphones


  /////////////// ANALYSER FFTSIZE ////////////////////////
  analyser.fftSize = 16384;

  // (FFT) is an algorithm that samples a signal over a period of time
  // and divides it into its frequency components (single sinusoidal oscillations).
  // It separates the mixed signals and shows what frequency is a violent vibration.

  // (FFTSize) represents the window size in samples that is used when performing a FFT

  // Lower the size, the less bars (but wider in size)
  ///////////////////////////////////////////////////////////


  const bufferLength = analyser.frequencyBinCount; // (read-only property)
  // Unsigned integer, half of fftSize (so in this case, bufferLength = 8192)
  // Equates to number of data values you have to play with for the visualization

  // The FFT size defines the number of bins used for dividing the window into equal strips, or bins.
  // Hence, a bin is a spectrum sample, and defines the frequency resolution of the window.

  const dataArray = new Uint8Array(bufferLength); // Converts to 8-bit unsigned integer array
  // At this point dataArray is an array with length of bufferLength but no values
  console.log('DATA-ARRAY: ', dataArray) // Check out this array of frequency values!

  const WIDTH = canvas.width;
  const HEIGHT = canvas.height;
  console.log('WIDTH: ', WIDTH, 'HEIGHT: ', HEIGHT)

  const barWidth = (WIDTH / bufferLength) * 73;
  console.log('BARWIDTH: ', barWidth)

  console.log('TOTAL WIDTH: ', (117 * 10) + (118 * barWidth)) // (total space between bars)+(total width of all bars)

  let barHeight;
  let x = 0;

  function renderFrame() {
    requestAnimationFrame(renderFrame); // Takes callback function to invoke before rendering

    x = 0;

    analyser.getByteFrequencyData(dataArray); // Copies the frequency data into dataArray
    // Results in a normalized array of values between 0 and 255
    // Before this step, dataArray's values are all zeros (but with length of 8192)

    // ctx.fillStyle = "rgba(0,0,0,0.2)"; // Clears canvas before rendering bars (black with opacity 0.2)
    ctx.fillStyle = "rgba(153,153,153,0.2)";
    ctx.fillRect(0, 0, WIDTH, HEIGHT); // Fade effect, set opacity to 1 for sharper rendering of bars

    let r, g, b;
    let bars = 118 // Set total number of bars you want per frame

    for (let i = 0; i < bars; i++) {
      barHeight = (dataArray[i] * .5);

      if (dataArray[i] > 210) { // highest frequency
        r = 17;
        g = 65;
        b = 136;
      } else if (dataArray[i] > 200) { // high
        r = 3;
        g = 10;
        b = 83;
      } else if (dataArray[i] > 190) { // medium
        r = 36;
        g = 40;
        b = 85;
      } else if (dataArray[i] > 180) { // low
        r = 78;
        g = 78;
        b = 78;
      } else { // lowest
        r = 27;
        g = 27;
        b = 28;
      }


      ctx.fillStyle = `rgb(${r},${g},${b})`;
      ctx.fillRect(x, (HEIGHT - barHeight), barWidth, barHeight);

      x += barWidth + 1 // Gives 10px space between each bar
    }
  }

  audio.play();
  renderFrame();
};



